<?php

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use backend\modules\monitor\project\forms\ValueRandForm as FormsValueRandForm;
use common\enums\StatusEnum;
use common\enums\PointEnum;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\Point;
use common\models\base\SearchModel;
use common\helpers\ResultHelper;
use common\helpers\ExcelHelper;
use common\models\monitor\project\point\Value;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PointValueController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Value::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $from_date =  $request->get('from_date', date('Y-m-d', strtotime("-6 day")));
		$to_date = $request->get('to_date', date('Y-m-d'));
        $type = $request->get('type',null);
        $warn = $request->get('warn',null);
        $pid = $request->get('pid',null);


        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'event_time' => SORT_DESC,
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);
        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['=', 'status', StatusEnum::ENABLED])
            ->andFilterWhere(['type' => $type])
            ->andFilterWhere(['warn' => $warn])
            // 转化时间戳，结尾添加1天取值到选取日期
            ->andWhere(['between','event_time',strtotime($from_date),strtotime('+1 day', strtotime($to_date))])
            ->andFilterWhere(['pid' => $pid]);

        //关于YII框架Response content must not be an array的解决方法
        // \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'pointModel' => $pid?Point::findOne($pid):'',
            'type'  => $type,
            'warn'  => $warn,
            'from_date' => $from_date,
            'to_date'   => $to_date,
        ]);
    }


    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $pid = $request->get('pid', NULL);
        $model = $this->findModel($id);
        $model->pid = $pid ?: $model->pid;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $model->event_time = strtotime($model->event_time);
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

    /**
     * 审核
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxState()
    {
        $request = Yii::$app->request;

        $model = Value::findOne($request->get('id'));
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(['index', 'pid' => $model->pid])
                : $this->message($this->getError($model), $this->redirect(['index', 'pid' => $model->pid]), 'error');
        }
        return $this->renderAjax('ajax-state', [
            'model' => $model,
        ]);
    }
    /**
     * @param {*} $id
     * @return {*}
     * @throws: 批量删除数据|修改数据状态
     */
    public function actionDestroyAll($id)
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);

        return Value::updateAll(['status' => StatusEnum::DISABLED], ['and', ['in', 'id', $data], ['pid' => $id]]);
    }

    public function actionRecyle($pid)
    {
        $pointModel = Point::findOne($pid);

        $query = Value::find()
            ->andWhere(['pid' => $pid])
            ->andWhere(['status' => StatusEnum::DISABLED])
            ->orderBy('id desc');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('recyle', [
            'pointModel' => $pointModel,
            'dataProvider' => $dataProvider,
        ]);
    }


    public function actionShow($id)
    {
        $model = Value::findOne($id);
        $model->status = StatusEnum::ENABLED;
        return $model->save()
            ? $this->redirect(Yii::$app->request->referrer)
            : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
    }


    /**
     * 监测点指定时间内数据
     *
     * @param $type
     * @return array
     */
    public function actionValueBetweenCount($type, $id)
    {
        $data = Yii::$app->services->pointValue->getBetweenCountStat($type, $id);

        return ResultHelper::json(200, '获取成功', $data);
    }



    /**
     * 服务器探针
     *
     * @return array|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionProbe()
    {
        $request = Yii::$app->request;
        $time = $request->get('time');
        $id = $request->get('id');
        $type = $request->get('type');
        $info = $this->getProbeInfo($time, $id, $type);
        if (Yii::$app->request->isAjax) {
            return ResultHelper::json(200, '获取成功', $info);
        }

        return $this->render('probe', [
            'info' => $info,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $request  = Yii::$app->request;
        $model = Value::find()
            ->with('parent')
            ->where(['id' => $id])
            ->asArray()
            ->one();
        switch ($model['parent']['type']) {
            case PointEnum::ANGLE:
                if ($model['value'] < 0) {
                    $model['news'] = $model['parent']['news'] + 4 > 8 ? $model['parent']['news'] - 4 : $model['parent']['news'] + 4;
                } else {
                    $model['news'] = $model['parent']['news'];
                }


                break;

            default:
                # code...
                break;
        }
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
	 * 导出Excel
	 *
	 * @return bool
	 * @throws \PhpOffice\PhpSpreadsheet\Exception
	 * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
	 */
	public function actionExport()
	{
		// [名称, 字段名, 类型, 类型规则]
		$request = Yii::$app->request;
		
        $from_date =  $request->get('from_date', date('Y-m-d', strtotime("-6 day")));
		$to_date = $request->get('to_date', date('Y-m-d'));
        $type = $request->get('type',null);
        $warn = $request->get('warn',null);
        $pid = $request->get('pid',null);

        $model = Value::find()
            ->with('parent')
            ->andWhere(['status' => StatusEnum::ENABLED])
            // 转化时间戳，结尾添加1天取值到选取日期
            ->andWhere(['between','event_time',strtotime($from_date),strtotime('+1 day', strtotime($to_date))])
            ->andFilterWhere(['pid' => $pid])
            ->andFilterWhere(['type' => $type])
            ->andFilterWhere(['warn' => $warn])
            ->andFilterWhere(['pid' => $pid])
            ->orderBy('event_time desc')
            ->asArray()
            ->all();
		$header = [            
			['采集时间', 'event_time', 'date', 'Y-m-d H:i:s'],
            ['类型', 'type', 'selectd',ValueTypeEnum::getMap()],
			['数据', 'value', 'text'],
			['报警', 'warn', 'selectd',WarnEnum::getMap()],
		];
		return ExcelHelper::exportData($model, $header, '数据导出_' . time());
	}

    /**
     * 探针
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getProbeInfo($time, $id, $type)
    {
        $status = true;
        $model = PointEnum::getModel($type);
        $oneModel = Point::findOne($id);
        $pointModel = Point::find()
            ->where(['pid' => $oneModel->pid])
            ->andWhere(['=', 'status', StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $data = [];
        foreach ($pointModel as $key => $value) {
            $data[$value['title']] = $model::find()
                ->where(['pid' => $id])
                ->andWhere(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'event_time', $time, time()])
                ->asArray()
                ->one();
            if (!$data[$value['title']]) {
                $status = false;
            }
        }
        return [
            'status' => $status,
            'chartTime' => date('H:i:s', $time),
            'time' => time()
        ];
    }

    /**
	 * 根据时间生成数据
	 *
	 * @return mixed|string|\yii\web\Response
	 * @throws \yii\base\ExitException
	 */
	public function actionValueRand($id)
	{

		$model = new FormsValueRandForm();
		$model->pid = $id;
		// ajax 校验
		$this->activeFormValidate($model);
		if ($model->load(Yii::$app->request->post())) {
			$model->start_time = strtotime($model->start_time);
			$model->end_time = strtotime($model->end_time);
		    return $model->save()
                ? $this->message('成功生成数据！', $this->redirect(Yii::$app->request->referrer), 'success')
				: $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
		}
		return $this->renderAjax($this->action->id, [
			'model' => $model,
		]);
	}


    /**
     * 导入表格
     * 
     * @return string
     */
    public function actionExcelFile($pid)
    {
        if (Yii::$app->request->isPost) {
            try {
                $file = $_FILES['excelFile'];
                $data = ExcelHelper::import($file['tmp_name'], 2);
                $i = Value::addDatas($pid, $data);
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }

            return $this->message('导入成功' . $i . '条数据', $this->redirect(['index']));
        }

        return $this->renderAjax($this->action->id);
    }

    /**
     * 下载
     */
    public function actionDownload()
    {
        $file = 'house-default.xls';

        $path = Yii::getAlias('@backend') . '/modules/monitor/file/' . $file;

        Yii::$app->response->sendFile($path, '权限数据_' . time() . '.xls');
    }
}

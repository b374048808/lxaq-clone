<?php

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use backend\modules\monitor\project\forms\BatchPointForm;
use common\enums\StatusEnum;
use common\enums\TimeUnitEnum;
use common\enums\ValueTypeEnum;
use common\models\monitor\project\Point;
use common\models\base\SearchModel;
use common\helpers\ResultHelper;
use common\models\monitor\project\log\WarnLog;
use common\enums\WarnEnum;
use common\models\monitor\project\point\HuaweiMap;
use common\models\monitor\project\point\Value;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PointController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Point::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', null);
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['pid' => $pid]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'pid'   => $pid
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
        $model = $this->findModel($id);

        $from_date =  $request->get('from_date', null);
        $to_date = $request->get('to_date', null);
        $type = $request->get('type', null);
        $warn = $request->get('warn', null);
        $pid = $request->get('pid', null);

        $searchModel = new SearchModel([
            'model' => Value::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'event_time' => SORT_DESC,
                'id' => SORT_DESC
            ],
            'pageSize' => '20'
        ]);
        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            // 转化时间戳，结尾添加1天取值到选取日期
            ->andFilterWhere(['between', 'event_time', $from_date ? strtotime($from_date) : null, $to_date ? strtotime('+1 day', strtotime($to_date)) : null])
            ->andFilterWhere(['pid' => $pid]);

        // p(Yii::$app->services->pointWarn->getDefaultWarn($id, 0));

        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'type'  => $type,
            'warn'  => $warn,
            'from_date' => $from_date,
            'to_date'   => $to_date,
        ]);
    }

    public function actionAjaxState($pid)
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);
        $model = new WarnLog();
        $model->pid = $pid ?: $model->pid;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $pointModel = Point::findOne($pid);
            $model->warn = $pointModel->warn;
            $pointModel->warn = WarnEnum::SUCCESS;
            $pointModel->save();
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        return $this->renderAjax('ajax-state', [
            'model' => $model,
        ]);
    }

    /**
     * 所有数据曲线图
     * 
     * @param number $id
     * @return mixed|array
     * @throws: 
     */
    public function actionDataChart($id)
    {
        $request = Yii::$app->request;
        $unit = TimeUnitEnum::getUnit($request->get('unit', TimeUnitEnum::DAY));    //天或小时
        $from_date = $request->get('from_date', date('Y-m-d', strtotime('-1 month')));  //开始时间
        $to_date = $request->get('to_date', date('Y-m-d')); //结束时间

        // 所有监测点
        $legends = ValueTypeEnum::getMap();


        $info['series'] = $info['chartTime'] = $info['legend'] = [];
        // 遍历所有时间点
        for ($i = strtotime($from_date); $i < strtotime($to_date); $i += $unit) {
            array_push($info['chartTime'], date($unit == TimeUnitEnum::getUnit(TimeUnitEnum::HOURS) ? 'm-d H:i' : 'm-d', $i));
        }
        // 遍历所有监测点
        foreach ($legends as $key => $value) {
            array_push($info['legend'], $value);
            $data = [];
            for ($i = strtotime($from_date); $i < strtotime($to_date); $i += $unit) {
                $_model = Value::find()
                    ->where(['pid' => $id])
                    ->andWhere(['between', 'event_time', $i, $i + $unit])
                    ->andWhere(['type' =>  $key])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->one();
                array_push($data, $_model['value']);
            }
            array_push($info['series'], [
                'name' => $value,
                'data' => $data,
            ]);
        }

        return $this->render($this->action->id, [
            'id' => $id,
            'info' => $info,
            'from_date' => $from_date,
            'to_date' => $to_date,
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
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

    /**
     * 监测点指定时间内数据
     *
     * @param number type
     * @return json|ResultHelper
     */
    public function actionValueBetweenChart($type, $id, $valueType)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->services->pointValue->getBetweenChartStat($type, $id, $valueType);

        return ResultHelper::json(200, '获取成功', $data);
    }
}

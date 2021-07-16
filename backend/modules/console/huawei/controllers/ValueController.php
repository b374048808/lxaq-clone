<?php

namespace backend\modules\console\huawei\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\console\iot\huawei\Value;
use common\models\base\SearchModel;
use common\models\console\iot\huawei\Service;
use common\helpers\ResultHelper;
use backend\modules\console\huawei\forms\AttrForm;
use common\helpers\ExcelHelper;
use common\models\console\iot\huawei\Device;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ValueController extends BaseController
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
    public function actionIndex($pid)
    {
        $request = Yii::$app->request;

        $from_date = $request->get('from_date', NULL);
        $to_date = $request->get('to_date', NULL);
        $startDate = $from_date ? strtotime($from_date) : NULL;
        $endDate = $to_date ? strtotime($to_date) : NULL;

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
            ->andWhere(['pid' => $pid])
            ->andFilterWhere(['between', 'event_time', $startDate, $endDate]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'pid' => $pid,
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
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $model->expiration_time = strtotime($model->expiration_time);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        $model->expiration_time =  $model->isNewRecord ? date('Y-m-d', strtotime('+1 year')) : date('Y-m-d', $model->expiration_time);
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }


    /**
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {

        return $this->renderAjax($this->action->id, [
            'model' => Value::findOne($id),
        ]);
    }


    /**
     * 设备数据历史曲线图
     *
     * @param $id
     * @return string
     */
    public function actionChart($pid,$service)
    {
        $request = Yii::$app->request;
        $type = $request->get('type');
        $from_date = $request->get('from_date', NULL);
        $to_date = $request->get('to_date', NULL);
        $startDate = $from_date ? strtotime($from_date) : NULL;
        $endDate = $to_date ? strtotime($to_date) : NULL;

        $query = Value::find()
            ->where(['pid' => $pid])
            ->andWhere(['serviceType' => $service])
            ->andFilterWhere(['between', 'event_time', $startDate, $endDate])
            ->groupBy('event_time');
        
        $_model = clone $query ;
        
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        $valueModel = $_model->asArray()->all();
        $info['legend'] = [$type];
        $info['time'] = $info['data'] = [];
        foreach ($valueModel as $key => $value) {
            if ($valueData = json_decode($value['value'],true)) {
                array_push($info['data'],$valueData[$type]);
                array_push($info['time'],date('m-d H:i',$value['event_time']));
                # code...
            }
        }
        
        return $this->render($this->action->id, [
            'pid' => $pid,
            'type' => $type,
            'service' => $service,
            'from_date' => $from_date,
            'to_date' => $to_date,
            'info' => $info,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionChartReal($pid)
    {
        $request = Yii::$app->request;
        $type = $request->get('type');
        $service = $request->get('service');

        $info = $this->getProbeInfo($request->get());
		if (Yii::$app->request->isAjax) {            
            return $info['success']
                ?ResultHelper::json(200, '获取成功', $info)
                :ResultHelper::json(300, '获取失败');
        }

        $deviceModel = Device::findOne($pid);
        global $cName;//定义
        $cName = $type;//赋值
        $serviceModel = Service::find()
            ->with(['attr' => function($queue){
                global $cName;//定义
                $queue->andWhere(['like','title',$cName]);
            }])
            ->where(['title' => $service])
            ->andWhere(['pid' => $deviceModel->pid])
            ->asArray()
            ->one();  
    
        $attr = $serviceModel['attr'][0];
        $info['legend'] = [$attr['title']];
        $info['unit'] = [$attr['unit']];
        $info['data'] = $attr['title'];
        return $this->render($this->action->id, [
            'pid' => $pid,
            'info' => $info,
            'service' => $service
        ]);
    }

    /**
     * 探针
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getProbeInfo($data)
    {
        $success =false;
        $systemInfo = [
            'time' => time()
        ];

        $num = 3;
        $num_arr = [];
        for ($i = 20; $i >= 1; $i--) {
            $num_arr[] = date('H:i:s', time() - $i * $num);
        }
        $valueModel = Value::find()
            ->where(['pid' => $data['pid']])
            ->andWhere(['serviceType' => $data['service']])
            ->andFilterWhere(['between','event_time',strtotime($data['startTime']),time()])
            ->asArray()
            ->one();
        $systemInfo['startTime'] = date('H:i:s', time());
        $systemInfo['data'] = $valueModel['value']?json_decode($valueModel['value'],true)[$data['type']]:null;
        $systemInfo['chartTime'] = $num_arr;
        $systemInfo['success'] = $valueModel?true:false;

        return $systemInfo;
    }

    /**
     * 导出Excel
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport($pid)
    {
        $request = Yii::$app->request;

        $from_date = $request->get('from_date', NULL);
        $to_date = $request->get('to_date', NULL);
        $startDate = $from_date ? strtotime($from_date) : NULL;
        $endDate = $to_date ? strtotime($to_date) : NULL;

        $model = Value::find()
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['pid' => $pid])
            ->andFilterWhere(['between', 'event_time', $startDate, $endDate])
            ->asArray()
            ->all();

        $header = [
            ['ID', 'id', 'text'],
            ['服务类型', 'serviceType', 'text'], // 规则不填默认text
            ['创建时间', 'event_time', 'date', 'Y-m-d'],
        ];
        $titles = [];
        foreach ($model ?: [] as $key => $value) {
            if (empty($value['value'])) {
                continue;
            }
            $value = json_decode($value['value'], true);
            $model[$key]['value']  = $value;
            foreach ($value as $k => $v) {
                if (in_array($k, $titles)) {
                    continue;
                }
                array_push($titles, $k);
                array_push($header, [$k, 'value.' . $k, 'text']);
            }
        }


        return ExcelHelper::exportData($model, $header);
    }
}

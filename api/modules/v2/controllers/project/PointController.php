<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-10 10:46:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 15:35:30
 * @Description: 
 */

namespace api\modules\v2\controllers\project;

use yii;
use common\enums\StatusEnum;
use common\models\monitor\project\Point;
use yii\data\ActiveDataProvider;
use common\models\monitor\project\House;
use api\controllers\BaseController;
use common\enums\PointEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\helpers\StringHelper;
use common\models\monitor\project\point\Value;

/**
 * 监测点控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class PointController extends BaseController
{
    public $modelClass = Point::class;
    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];
    /**
     * 首页
     *
     * @return ActiveDataProvider
     */
    public function actionIndex($pid)
    {
        $request = Yii::$app->request;
        $type = $request->get('type', PointEnum::ANGLE);

        $houseModel = House::findOne($pid);
        $model = Point::find()
            ->select(['title','pid','id'])
            ->with(['newValue','huaweiDevice','aliDevice'])
            ->where(['pid' => $pid])
            ->andWhere(['type' => $type])
            ->andWhere(['=', 'status', StatusEnum::ENABLED])
            ->orderBy('id desc')
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            $value['warn'] = Yii::$app->services->pointWarn->getPointWarn($value['id']);
            $value['newValue'] = isset($value['newValue'])?$value['newValue']['value']:0;            
            $value['device'] = $value['huaweiDevice'] || $value['aliDevice'];
            $value['warnText'] = WarnEnum::getValue($value['warn']);
            # code...
        }
        $info['cover'] = isset($houseModel[PointEnum::getCover($type)])?$houseModel[PointEnum::getCover($type)]:[];
        foreach ( $info['cover'] as $key => &$value) {
            # code...
            $value = @getimagesize(StringHelper::getThumbUrl($value,100,100))
                ? StringHelper::getThumbUrl($value,100,100)
                : $value;
        }
        $info['data'] = $model;
        return $info;
    }



    /**
     * @param {*} $id
     * @return {*}
     * @throws: 监测点详情，最新数据，设备最新数据
     */
    public function actionView($id)
    {
        $model = Point::find()
            ->with(['newValue','deviceValue'])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        
        $model['prevValue'] = Value::getPrevValue($model['newValue']['id']);
        $model['type'] = PointEnum::getValue($model['type']);
        $model['newValue'] = $model['newValue']['value'];
        $services = json_decode($model['deviceValue']['services'])[0]->data;
        $model['temperature'] = isset($services->temperature)?round($services->temperature,2):'-';
        $model['xinhao'] = isset($services->signalIntensity)?round($services->signalIntensity,2):'-';

        return [
            'data' => $model,
            'devices' => Yii::$app->services->point->getDeviceMap($id),
        ];
    }
    /**
     * 近三月数据，按月区分
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionChart($id)
    {
        $model  = $this->findModel($id);
        $request = Yii::$app->request;
        $month = $request->get('month', 0);
        $type =  $request->get('type', ValueTypeEnum::AUTOMATIC);
        if ($month == 0) {
            $sdefaultDate = date("Y-m-d");
            //$first =1 表示每周星期一为开始日期 0表示每周日为开始日期
            $config = [
                [
                    'title' => "本周",
                    'begin_time' => mktime(0,0,0,date('m'),date('d')-date('w')+1,date('y')),
                    'end_time' => time(),
                ],
                [
                    'title' => "上周",
                    'begin_time' => mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y')),
                    'end_time' => strtotime('-7 day'),
                ]
            ];
        } else {
            $config = [
                [
                    'title' => "本月",
                    'begin_time' => mktime(0, 0, 0, date('m'), 1, date('Y')),
                    'end_time' => time(),
                ],
                [
                    'title' => "上月",
                    'begin_time' => strtotime(date('Y-m-01 00:00:00', strtotime('-1 month'))),
                    'end_time' => strtotime(date("Y-m-".date('d',time())." 23:59:59", strtotime(-date('d') . 'day'))),
                ]
            ];
        }
        $info['time'] = $info['data']= [];
        for ($i = $config[0]['begin_time']; $i < $config[0]['end_time']; $i += 60 * 60 * 24) {
            array_push($info['time'], date('m-d', $i));
        }

        $model = Value::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['type' => $type])
            ->andWhere(['state' => ValueStateEnum::ENABLED]);
        foreach ($config as $key => $value) {
            $datas = [];
            $j = 0;
            for ($i = $value['begin_time']; $i < $value['end_time']; $i += 60 * 60 * 24) {
                $_model = clone $model;
                $_model = $_model->andWhere(['between', 'event_time', $i, $i + 60 * 60 * 24])
                    ->orderBy('value ASC')
                    ->asArray()
                    ->one();
                if (isset($_model['value'])) {
                    array_push($datas, $_model['value']);
                    $j = $_model['value'];
                } else {
                    array_push($datas, $j);
                }

                # code...
            }
            array_push($info['data'], ['name' => $value['title'], 'data' => $datas, 'format' => $datas]);
        }

        return $info;
    }
}

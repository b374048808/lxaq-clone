<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-10 10:46:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 15:23:33
 * @Description: 
 */

namespace api\modules\v2\controllers\project;

use yii;
use api\controllers\OnAuthController;
use common\enums\JudgeEnum;
use common\enums\NewsEnum;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\enums\PointEnum;
use common\enums\StructEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\models\member\HouseMap;
use common\models\monitor\project\House;
use yii\data\ActiveDataProvider;
use common\helpers\ArrayHelper;
use common\helpers\StringHelper;
use common\models\member\base\GroundMap;
use common\models\member\base\HouseMap as BaseHouseMap;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\AliMap;
use common\models\monitor\project\point\HuaweiMap;
use common\models\monitor\project\point\Value;
use common\models\monitor\project\rule\Item as RuleItem;
use common\models\monitor\rule\Child;

/**
 * 房屋控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class HouseController extends OnAuthController
{
    public $modelClass = House::class;

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
    public function actionIndex($start = 0, $limit = 20)
    {
        $houseIds = Yii::$app->services->memberHouse->getHouseId(Yii::$app->user->identity->member_id);

        $request = Yii::$app->request;
        $title = $request->get('title', NULL);
        $query = House::find()
            ->select([
                'title', 'id', 'cover',
                'address', 'status'
            ])
            ->with(['point' => function ($queue) {
                $queue->groupBy('type')->select(['title', 'type', 'pid']);
            }])
            ->andWhere(['in', 'id', $houseIds])
            ->andFilterWhere([
                'or',
                ['like', 'address', $title],
                ['like', 'title', $title]
            ])
            ->andWhere(['=', 'status', StatusEnum::ENABLED])
            ->orderBy('id desc');
        $count = $query->count();
        $model = $query
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            $value['cover'] = @getimagesize(StringHelper::getThumbUrl($value['cover'], 100, 100)) ? StringHelper::getThumbUrl($value['cover'], 100, 100) : '';
            $value['warn'] = Yii::$app->services->pointWarn->getHouseWarn($value['id']);
            $value['warnText'] = WarnEnum::getValue($value['warn']);
            $type = ArrayHelper::getColumn($value['point'], 'type');
            $value['type'] = '';
            foreach ($type as $key => $v) {
                $value['type'] .= ' ' . PointEnum::getValue($v);
            }
        }

        return [
            'data' => $model,
            'count' => $count,
        ];
    }


    /**
     * 房屋详情
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionView($id)
    {
        $model = House::find()
            ->with('warn')
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $model['cover'] = @getimagesize(StringHelper::getThumbUrl($model['cover'], 100, 100))
            ? StringHelper::getThumbUrl($model['cover'], 100, 100)
            : null;
        // 房屋规则报警值
        $houseItem = [];
        $ruleModel = RuleItem::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach (PointEnum::getMap() as $key => $value) {
            $houseItem[$key] = [
                'alert' => PointEnum::getAlert($key),
                'title' => $value,
                'data' => []
            ];
        }
        // 规则
        foreach ($ruleModel as $key => $value) {
            array_push($houseItem[$value['type']]['data'], [
                'warn' => WarnEnum::getValue($value['warn']),
                'judge' => JudgeEnum::getValue($value['judge']) . $value['value'],
            ]);
        }
        $model['warns'] = $houseItem;
        $model = $this->getArrayCover($model);
        // 通知
        $info['notify'] = [];
        $warnModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['>', 'warn', WarnEnum::SUCCESS])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('warn desc')
            ->asArray()
            ->all();
        foreach ($warnModel as $key => $value) {
            array_push($info['notify'], [
                'type' => PointEnum::getValue($value['type']),
                'title' => $value['title'],
                'warnText' => WarnEnum::getValue($value['warn']),
                'warn' =>  $value['warn'],
            ]);
            array_push($info['notify'], [
                'type' => PointEnum::getValue($value['type']),
                'title' => $value['title'],
                'warnText' => WarnEnum::getValue($value['warn']),
                'warn' =>  $value['warn'],
            ]);
        }
        // 监测点数量
        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED]);
        $model['point_count'] = $pointModel->count();
        $_pointModel = $pointModel->asArray()
            ->all();
        $pointIds = ArrayHelper::getColumn($_pointModel, 'id', $keepKeys = true);
        // 查询绑定的设备数量
        $aliCount = AliMap::find()
            ->where(['in', 'point_id', $pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->groupBy('device_id')
            ->count();
        $huaweiCount = HuaweiMap::find()
            ->where(['in', 'point_id', $pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->groupBy('device_id')
            ->count();
        $model['device_count'] = $aliCount + $huaweiCount;

        $model['warn'] = $model['warn']['warn'] ?: 0;
        $model['warnText'] = isset($model['warn']['warn']) ? WarnEnum::getValue($model['warn']['warn']) : WarnEnum::getValue(WarnEnum::SUCCESS);
        $info['data'] = $model;


        return $info;
    }


    /**
     * @param {*} $id
     * @param {*} $type
     * @param {*} $chartType
     * @return array
     * @throws: 近期数据，1~3月
     */
    public function actionChart($id, $type = PointEnum::ANGLE, $chartType = ValueTypeEnum::AUTOMATIC)
    {
        $request = Yii::$app->request;
        $month = $request->get('month');

        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $begin_time = strtotime(date('Y-' . $month . '-1'));    //开始时间
        $toDay = date("t", $begin_time);    //天数
        $info['time'] = $info['data'] = $time = [];
        // 遍历所有日期
        for ($i = 0; $i < $toDay; $i++) {
            array_push($info['time'], date('m-d', $begin_time + $i * 86400));
            array_push($time, date('Y-m-d', $begin_time + $i * 86400));
        }

        $res = [];
        // 最大值|最小值
        foreach ($pointModel as $key => $value) {
            $res['title'] = $value['title'];
            $res['data'] = [];
            $res['min'] = $res['max'] = null;
            $model = Value::find()
                ->where(['pid' => $value['id']])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['type' => $chartType])
                ->andWhere(['state' => ValueStateEnum::ENABLED]);
            // 取前一个最后数据为开始
            $oldModel = clone $model;
            $oldModel = $oldModel
                ->andWhere(['<', 'event_time', $begin_time])
                ->orderBy('event_time DESC')
                ->asArray()
                ->one();
            $j = $oldModel['value'] ?: 0;
            foreach ($time as $k => $v) {
                $_model = clone $model;
                $valueModel = $_model
                    ->andWhere(['between', 'event_time', strtotime($v), strtotime($v) + 86400])
                    ->asArray()
                    ->one();
                // 最大小值为空时，赋值
                if (empty($res['min']) && isset($valueModel['value'])) {
                    $res['min']['value'] = $valueModel['value'];
                    $res['min']['date'] = date('m-d', $valueModel['event_time']);
                }
                if (empty($res['max']) && isset($valueModel['value'])) {
                    $res['max']['value'] = $valueModel['value'];
                    $res['max']['date'] = date('m-d', $valueModel['event_time']);
                }
                // 判断大小赋值
                if ($valueModel['value'] > $res['max']['value'] && isset($valueModel['value'])) {
                    $res['max']['value'] = $valueModel['value'];
                    $res['max']['date'] = date('m-d', $valueModel['event_time']);
                }
                if ($valueModel['value'] < $res['min']['value'] && isset($valueModel['value'])) {
                    $res['min']['value'] = $valueModel['value'];
                    $res['min']['date'] = date('m-d', $valueModel['event_time']);
                }
                $j = isset($valueModel['value']) ? $valueModel['value'] : $j;
                array_push($res['data'], $j);
            }
            array_push($info['data'], $res);
        }

        return $info;
    }

    /**
     * 房屋数据转化
     * 
     * @param array
     * @return array
     * @throws: 
     */
    public function getArrayCover($data)
    {
        $data['layout_cover'] = $data['layout_cover'] ? json_decode($data['layout_cover']) : [];
        $data['angle_cover'] = $data['angle_cover'] ? json_decode($data['angle_cover']) : [];
        $data['cracks_cover'] = $data['cracks_cover'] ? json_decode($data['cracks_cover']) : [];
        $data['settling_cover'] = $data['settling_cover'] ? json_decode($data['settling_cover']) : [];
        $data['move_cover'] = $data['move_cover'] ? json_decode($data['move_cover']) : [];
        $data['news'] = NewsEnum::getValue($data['news']);
        $data['nature'] = StructEnum::getNatureValue($data['nature']);
        $data['type'] = StructEnum::getTypeValue($data['type']);
        $data['roof'] = StructEnum::getRootValue($data['roof']);

        return $data;
    }
}

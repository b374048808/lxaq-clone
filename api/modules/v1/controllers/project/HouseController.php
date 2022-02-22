<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-10 10:46:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-23 10:27:59
 * @Description: 
 */

namespace api\modules\v1\controllers\project;

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
use yii\data\Pagination;
use common\helpers\ArrayHelper;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\AliMap;
use common\models\monitor\project\point\HuaweiMap;
use common\models\monitor\project\point\Value;
use common\models\monitor\project\rule\Item;
use common\models\monitor\rule\Child;
use Swoole\Http\Status;

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
    public function actionIndex($page = 1, $limit = 20)
    {
        $houseIds =  $houseIds = Yii::$app->services->memberHouse->getHouseId(Yii::$app->user->identity->member_id);;

        $request = Yii::$app->request;
        $title = $request->get('title', NULL);
        $query = House::find()
            ->select([
                'title', 'id', 'cover',
                'address', 'status', 'lng', 'lat'
            ])
            ->with(['point' => function ($queue) {
                $queue->groupBy('type')->select(['title', 'type', 'pid']);
            }])
            ->andWhere(['in', 'id', $houseIds])
            ->andFilterWhere(['like', 'titile', $title])
            ->andWhere(['=', 'status', StatusEnum::ENABLED]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $limit = 20]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        foreach ($model as $key => &$value) {
            $value['warnText'] = WarnEnum::getValue(Yii::$app->services->pointWarn->getHouseWarn($value['id']));
            $type = ArrayHelper::getColumn($value['point'], 'type');
            $value['type'] = '';
            foreach ($type as $key => $v) {
                $value['type'] .= ' ' . PointEnum::getValue($v);
            }
        }

        return [
            'data' => $model,
            'pages' => $pages,
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
            ->where(['id' => $id])
            ->asArray()
            ->one();

        // 房屋规则报警值
        $ruleItem = Item::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $houseItem = [];
        foreach (PointEnum::getMap() as $key => $value) {
            $houseItem[$key] = [
                'alert' => PointEnum::getAlert($key),
                'title' => $value,
                'data' => []
            ];
        }
        // 规则
        foreach ($ruleItem as $key => $value) {
            array_push($houseItem[$value['type']]['data'], [
                'warn' => WarnEnum::getValue($value['warn']),
                'judge' => JudgeEnum::getValue($value['judge']) . $value['value'],
            ]);
        }
        $model['warns'] = $houseItem;
        $model = $this->getArrayCover($model);

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

        $model['warn'] = Yii::$app->services->pointWarn->getHouseWarn($model['id']);
        $model['warnText'] = $model['warn'] ? WarnEnum::getValue($model['warn']) : WarnEnum::getValue(0);


        return $model;
    }


    /**
     * @param {*} $id
     * @param {*} $type
     * @param {*} $chartType
     * @return array
     * @throws: 
     */
    public function actionChart($id, $type = PointEnum::ANGLE, $chartType = ValueTypeEnum::AUTOMATIC)
    {
        // 遍历所有类型监测
        $model  = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $res['chartTime'] = $res['data'] = $res['legend'] = [];
        // 时间，X轴
        for ($i = strtotime('-3 month'); $i < time(); $i += 60 * 60 * 24) {
            array_push($res['chartTime'], date('m-d', $i));
        }
        $res['until'] = PointEnum::$Until[$type];   //单位
        foreach ($model as $key => &$value) {
            array_push($res['legend'], $value['title']);
            $dataArray = [];
            $j = 0;
            for ($i = strtotime('-3 month'); $i < time(); $i += 60 * 60 * 24) {
                $data = Value::find()
                    ->where(['pid' => $value['id']])
                    ->andwhere(['type' => $chartType])
                    ->andWhere(['between', 'event_time', $i, $i + 60 * 60 * 24])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['state' => ValueStateEnum::ENABLED])
                    ->orderBy('event_time desc')
                    ->asArray()
                    ->one();
                // 不是最新版本echart数据为null会断开，目前npm最新版本不能实现
                $j = $data['value'] ?: $j;
                array_push($dataArray, $data ? $data['value'] : $j);
            }
            // 数据
            array_push($res['data'], [
                'title' => $value['title'],
                'data' => $dataArray
            ]);
        }


        // 房屋规则报警值
        $ruleItem = Item::find()
            ->where(['pid' => $id])
            ->andWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $res['warns'] = ArrayHelper::getColumn($ruleItem, 'value');



        return $res;
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

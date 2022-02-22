<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:42:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-30 15:22:06
 * @Description: 
 */

namespace datav\modules\v1\controllers\project;

use yii;
use api\controllers\OnAuthController;
use common\enums\NewsEnum;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\enums\StructEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\House;
use common\models\monitor\project\Point;

/**
 * 默认控制器
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
    protected $authOptional = ['index', 'search', 'area-map', 'view'];

    /**
     * 房屋坐标
     * 
     * 
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $res = [];
        $res['warn'] = [];
        // 点位按预警分组
        $info = [];
        foreach (WarnEnum::getMap() as $key => $value) {
            $info[$key + 1] = [];
        }
        $query = House::find()
            ->with(['point', 'simple'])
            ->where(['status' => StatusEnum::ENABLED]);
        $res['count'] = $query->count();
        $model = $query
            ->asArray()
            ->all();

        foreach (WarnEnum::getMap() as $key => $value) {
            $key < 4 &&
            array_push(
                $res['warn'],
                [
                    'title' => $value,
                    'value' => 0,
                ]

            );
            # code...
        }
        foreach ($model as $key => &$value) {
            if ($value['lat'] == 0 || $value['lng'] == 0 || $value['lat'] == null || $value['lng'] == null) {
                continue;
            }
            // 查询报警
            $values = [];
            foreach ($value['point'] as $k => $val) {
                $pointModel = Point::find()
                    ->with('newValue')
                    ->where(['id' => $val['id']])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->one();

                if ($pointModel) {
                    $warn = yii::$app->services->pointWarn->getPointWarn($val['id']);
                    $data = [
                        'point' => $pointModel['title'],
                        'event_time' => date('m-d H:i', $pointModel['newValue']['event_time']),
                        'value' => $pointModel['newValue']['value'],
                        'warn' => $warn,
                        'warn_text' => WarnEnum::getValue($warn),
                    ];
                    array_push($values, $data);
                }
            }
            $simple = [];
            if ($value['simple']) {
                foreach ($value['simple'] as $key => $val) {
                    # code...
                    array_push($simple, [
                        'type'  => PointEnum::getValue($val['type']),
                        'value' => $val['value'],
                        'warn'  => WarnEnum::getValue($val['warn'])
                    ]);
                }
            }


            $warn = yii::$app->services->pointWarn->getHouseWarn($value['id']);
            $res['warn'][$warn]['value']++;
            array_push($info[$warn + 1], [
                'simple' => $simple,
                'values' => $values,
                'view'  => $value,
                'id'    => $value['id'],
                'lat' => $value['lat'],
                'lng' => $value['lng'],
                'name'  => $value['title'],
                'warn' => $warn + 1,
                'value' => ($warn + 1) * 50,
                'type' => 1,
            ]);
            # code...
        }
        unset($value);
        $res['map'] = $info;


        return $res;
    }


    public function actionAreaMap()
    {
        // 温州地区房屋
        $model = House::find()
            ->select('count(id) as count,area_id')
            ->where(['>', 'area_id', 0])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['city_id' => '330300'])
            ->groupBy('area_id')
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            $value['text'] = Yii::$app->services->provinces->getName($value['area_id']);
            # code...
        }
        unset($value);
        return $model;
    }

    public function actionView($id)
    {
        $model = House::find()
            ->with(['point' => function ($queue) {
                $queue->select(['id', 'title', 'type', 'pid']);
            }])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $model = $this->getArrayCover($model);
        $model['warn'] = Yii::$app->services->pointWarn->getHouseWarn($id);
        return $model;
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

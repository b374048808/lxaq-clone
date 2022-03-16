<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-19 14:50:10
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-10 17:16:13
 * @Description: 监测首页
 */

namespace backend\modules\monitor\lk\controllers;

use Yii;
use common\traits\Curd;
use common\helpers\ResultHelper;
use backend\controllers\BaseController;
use common\helpers\ArrayHelper;
use common\models\monitor\project\House;
use common\models\monitor\project\point\HuaweiMap;
use yii\data\Pagination;

class MonitorController extends BaseController
{

    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = HuaweiMap::class;
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {



        // 房屋数量
        $houseData =  HuaweiMap::find()->with('house')->asArray()->all();
        $houseKey = ArrayHelper::index($houseData, null, function ($element) {
            return $element['house']['id'];
        });
        // 设备数量
        $_model =  HuaweiMap::find();
        $deviceCount = $_model->groupBy('device_id')
            ->count();

        //查看绑定了设备的
        $data  = HuaweiMap::find()
            ->with(['house', 'device', 'point', 'value']);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->limit($pages->limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();


        $result = ArrayHelper::index($models, null, function ($element) {
            return $element['house']['id'];
        });

        return $this->render($this->action->id, [
            'models' => $result,
            'pages' => $pages,
            'pointCount' => $data->count(),
            'houseCount' => count($houseKey),
            'deviceCount' => $deviceCount
        ]);
    }

    /**
     * 倾斜指定时间内数量
     *
     * @param $type
     * @return array
     */
    public function actionAnglePointBetweenCount($type)
    {
        $data = Yii::$app->services->angleValue->getPointBetweenCount($type);

        return ResultHelper::json(200, '获取成功', $data);
    }
    /**
     * 裂缝指定时间内数量
     *
     * @param $type
     * @return array
     */
    public function actionCracksPointBetweenCount($type)
    {
        $data = Yii::$app->services->cracksValue->getPointBetweenCount($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 沉降指定时间内数量
     *
     * @param $type
     * @return array
     */
    public function actionSinkPointBetweenCount($type)
    {
        $data = Yii::$app->services->sinkValue->getPointBetweenCount($type);

        return ResultHelper::json(200, '获取成功', $data);
    }
}

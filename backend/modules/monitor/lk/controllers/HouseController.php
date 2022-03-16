<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-19 14:50:10
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-25 14:03:38
 * @Description: 监测首页
 */

namespace backend\modules\monitor\lk\controllers;

use Yii;
use backend\controllers\BaseController;
use common\models\monitor\project\point\HuaweiMap;
use common\helpers\ArrayHelper;
use yii\data\Pagination;

class HouseController extends BaseController
{
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
            ->with(['house', 'point', 'device', 'value']);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('point_id desc')
            ->limit($pages->limit)
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
}

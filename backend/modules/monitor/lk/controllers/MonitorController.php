<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-19 14:50:10
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-04-30 08:57:07
 * @Description: 监测首页
 */

namespace backend\modules\monitor\lk\controllers;

use Yii;
use backend\forms\ClearCache;
use common\helpers\ResultHelper;
use backend\controllers\BaseController;

class MonitorController extends BaseController
{
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render($this->action->id, [
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
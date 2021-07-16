<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-24 15:29:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 11:55:51
 * @Description: 
 */

namespace console\controllers;

use common\enums\StatusEnum;
use common\models\monitor\create\Simple;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;

/**
 * 提醒服务
 *
 * Class IotController
 * @package console\controllers
 */
class RandController extends Controller
{

    public function actionSimple()
    {
        // 所有时间为结束联动
        $model = Simple::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['>','end_time',time()])
            ->asArray()
            ->all();
        foreach ($model as $key => $value) {
            Yii::$app->services->createSimple->timingRand($value['id']);
        }       
        Console::stdout('成功生成数据');
        exit;
    }
}
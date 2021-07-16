<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-24 15:29:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 15:34:18
 * @Description: 
 */

namespace console\controllers;

use common\enums\monitor\SubscriptionActionEnum;
use common\enums\monitor\SubscriptionReasonEnum;
use common\enums\StatusEnum;
use common\models\sim\vlist\Card;
use Yii;
use yii\console\Controller;
use yii\helpers\Console;
use common\models\console\iot\ali\Device;
use common\models\console\iot\huawei\Device as HuaweiDevice;

/**
 * 提醒服务
 *
 * Class IotController
 * @package console\controllers
 */
class BellController extends Controller
{
    /**
     * 
     *  物联网卡过期提醒
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionCard()
    {
        // 监测所有过期卡号
        $model = Card::find()
            ->where(['<', 'expiration_time', time()])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($model as $key => $value) {
            $day = (int)((time() - $value['expiration_time']) / 24 / 3600);
            $content = '物联网卡号' . $value['iccid'] . '已过期' . $day . '天';
            Console::stdout($content);
            Yii::$app->services->monitorNotify->createRemind(
                $value['id'],
                SubscriptionReasonEnum::BEHAVIOR_CREATE,
                SubscriptionActionEnum::OVER_TIME,
                $content,
            );
        }
        exit();
    }



    /**
     * 
     *  阿里设备离线异常
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionAliDevice()
    {
        $deviceModel = Device::find()
            ->with(['newValue'])
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($deviceModel as $key => $value) {
            # code...
            if ($value['newValue']['event_time'] / 1000 < strtotime('-1 day')) {
                $day = (int)((time() - $value['newValue']['event_time']/1000) / 24 / 3600);
                $content = '阿里设备编号' . $value['number'] . '已有' . $day . '天没有接收到数据';
                Console::stdout($content);
                Yii::$app->services->monitorNotify->createRemind(
                    $value['id'],
                    SubscriptionReasonEnum::BEHAVIOR_CREATE,
                    SubscriptionActionEnum::ALI_OFFLINE,
                    $content,
                );
                # code...
            }
        }
    }

    /**
     * 
     *  华为设备离线异常
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionHuaweiDevice()
    {

        $deviceModel = HuaweiDevice::find()
            ->with(['newValue'])
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($deviceModel as $key => $value) {
            # code...
            if ($value['newValue']['event_time']  < strtotime('-1 day')) {
                $day = (int)((time() - $value['newValue']['event_time']) / 24 / 3600);
                $content = '华为设备编号' . $value['number'] . '已有' . $day . '天没有接收到数据';
                Console::stdout($content);
                Yii::$app->services->monitorNotify->createRemind(
                    $value['id'],
                    SubscriptionReasonEnum::BEHAVIOR_CREATE,
                    SubscriptionActionEnum::HUAWEI_OFFLINE,
                    $content,
                );
                # code...
            }
        }
    }
}

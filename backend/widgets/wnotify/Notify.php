<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 09:01:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 09:17:09
 * @Description: 
 */

namespace backend\widgets\wnotify;

use Yii;
use yii\base\Widget;

/**
 * Class Notify
 * @package backend\widgets\notify
 * @author jianyan74 <751393839@qq.com>
 */
class Notify extends Widget
{
    /**
     * @return string
     * @throws \yii\db\Exception
     */
    public function run()
    {
        // 拉取公告
        Yii::$app->services->wrokerNotify->pullAnnounce(Yii::$app->user->id, Yii::$app->user->identity->created_at);
        // 拉取订阅
        if ($config = Yii::$app->services->wrokerNotifySubscriptionConfig->findByMemberId(Yii::$app->user->id)) {
            Yii::$app->services->wrokerNotify->pullRemind($config);
        }

        // 获取当前通知
        list($notify, $notifyPage) = Yii::$app->services->wrokerNotify->getUserNotify(Yii::$app->user->id);

        return $this->render('notify', [
            'notify' => $notify,
            'notifyPage' => $notifyPage,
        ]);
    }
}
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-01-06 09:20:58
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-06 09:30:09
 * @Description: 
 */

namespace common\helpers;

use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\models\worker\MiniMessage as MiniMessageModel;
use Yii;

/**
 * Class MigrateHelper
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class MiniHelper
{

    public static function getItemNumber($title = '', $member_name = '')
    {
        // 微信通知
        $messageModel = MiniMessageModel::findAll([
            'is_read' => 0,
            'action' => MessageActionEnum::NUMBER_REMIND,
            'target_type' => MessageReasonEnum::ITEM_VERIFY,
        ]);
        $data = [
            'thing3' => [
                'value' => mb_substr($title, 0, 15),
            ],
            'thing1' => [
                'value' => $member_name,
            ],
        ];
        foreach ($messageModel as $key => $value) {
            Yii::$app->services->workerMiniMessage->send($value->id, $data);
            # code...
        }
    }
}

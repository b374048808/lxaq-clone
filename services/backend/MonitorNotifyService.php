<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-14 14:52:53
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-14 15:23:42
 * @Description: 
 */

namespace services\backend;

use Yii;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\backend\MonitorNotify;

/**
 * Class NotifyService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class MonitorNotifyService extends Service
{
    /**
     * 创建提醒
     *
     * @param int $target_id 触发id
     * @param string $targetType 触发类型
     * @param string $action 提醒关联动作
     * @param int $sender_id 发送者(用户)id
     * @param string $content 内容
     */
    public function createRemind($target_id, $targetType, $action, $content)
    {
        $model = new MonitorNotify();
        $model->target_id = $target_id;
        $model->target_type = $targetType;
        $model->content = $content;
        $model->action = $action;
        return $model->save();
    }
}
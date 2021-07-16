<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-14 14:47:30
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-14 15:47:33
 * @Description: 
 */

namespace common\models\backend;

use Yii;
use common\enums\StatusEnum;

/**
 * This is the model class for table "rf_lx_monitor_notify".
 *
 * @property int $id 主键
 * @property string $title 标题
 * @property string $content 消息内容
 * @property int $target_id 目标id
 * @property string $target_type 目标类型
 * @property int $target_display 接受者是否删除
 * @property string $action 动作
 * @property int $view 浏览量
 * @property int $sender_withdraw 是否撤回 0是撤回
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MonitorNotify extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_notify';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['content'], 'string'],
            [['target_id', 'target_display', 'view', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 150],
            [['target_type', 'action'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'target_id' => 'Target ID',
            'target_type' => 'Target Type',
            'target_display' => 'Target Display',
            'action' => 'Action',
            'view' => 'View',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 关联发送用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSenderForMember()
    {
        return $this->hasOne(Member::class, ['id' => 'sender_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getNotifyMember()
    {
        return $this->hasOne(NotifyMember::class, ['notify_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessageMember()
    {
        return $this->hasOne(NotifyMember::class, ['notify_id' => 'id'])->with('member');
    }

}

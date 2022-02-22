<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-22 11:00:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-22 15:25:13
 * @Description: 
 */
namespace common\models\monitor\project\service;

use Yii;

/**
 * This is the model class for table "{{%backend_notify_member}}".
 *
 * @property int $id
 * @property string $member_id 管理员id
 * @property int $service_id 消息id
 * @property int $is_read 是否已读 1已读
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class ServiceMember extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lx_monitor_service_member}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'service_id', 'is_read',  'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '管理员',
            'service_id' => 'Service ID',
            'is_read' => '是否已读',
            'status' => '状态',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 关联消息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    /**
     * 关联消息和用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceSenderForMember()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id'])->with(['senderForMember']);
    }

    /**
     * 关联消息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getServiceSend()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    /**
     * 关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getMember()
    {
        return $this->hasOne(Member::class, ['id' => 'member_id']);
    }

}

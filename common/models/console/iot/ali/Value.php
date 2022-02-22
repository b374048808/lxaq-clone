<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-22 08:41:28
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-20 15:44:01
 * @Description: 
 */

namespace common\models\console\iot\ali;

use Yii;

/**
 * This is the model class for table "rf_lx_iot_ali_device_value".
 *
 * @property int $id
 * @property int $pid 设备ID
 * @property int $qos 消息ID
 * @property int $message_id 消息ID
 * @property array $message 整体消息数据
 * @property string $destination 目的
 * @property string $topic 主题
 * @property int $subscription 订阅
 * @property int $event_time 云端时间
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property string $body 内容
 */
class Value extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_ali_device_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'qos', 'message_id', 'subscription', 'event_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['message'], 'safe'],
            [['destination', 'topic'], 'string', 'max' => 50],
            [['body'], 'string', 'max' => 250],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'qos' => 'Qos',
            'message_id' => 'Message ID',
            'message' => 'Message',
            'destination' => 'Destination',
            'topic' => 'Topic',
            'subscription' => 'Subscription',
            'event_time' => 'Event Time',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'body' => 'Body',
        ];
    }

    public function getDevice()
    {
        return $this->hasOne(Device::class,['id' => 'pid']);
    }

    public function getProduct()
    {
        return $this->hasOne(Product::class,['id' => 'pid'])
            ->viaTable(Device::tableName(),['id' => 'pid']);
    }
}

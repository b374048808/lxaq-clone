<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 09:53:26
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 10:53:12
 * @Description: 
 */

namespace common\models\console\iot\huawei;

use Yii;

/**
 * This is the model class for table "rf_lx_iot_huawei_device_value".
 *
 * @property int $id
 * @property int $pid
 * @property array $value
 * @property int $status 状态
 * @property int $event_time 云端时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property string $deviceId 设备ID
 * @property string $gatewayId 网关Id
 * @property string $notifyType 消息类型
 * @property array $services 整体数据
 */
class Value extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_huawei_device_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'status', 'event_time', 'created_at', 'updated_at'], 'integer'],
            [['value', 'services'], 'safe'],
            [['deviceId', 'gatewayId', 'serviceType', 'notifyType'], 'string', 'max' => 50],
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
            'value' => '数据',
            'serviceType' => '服务类型',
            'status' => '状态',
            'event_time' => '数据发送时间',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'deviceId' => '设备ID',
            'gatewayId' => '网关ID',
            'notifyType' => '消息类型',
            'services' => '原始数据',
        ];
    }


    /**
     * 关联产品
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct(){
        return $this->hasOne(Product::class,['id' => 'pid'])
        ->viaTable(Device::tableName(),['id' => 'pid']);
    }

    /**
     * 关联设备
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevice(){
        return $this->hasOne(Device::class,['id' => 'pid']);
    }
}

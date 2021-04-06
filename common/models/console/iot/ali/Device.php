<?php

namespace common\models\console\iot\ali;

use Yii;

/**
 * This is the model class for table "rf_lx_iot_ali_device".
 *
 * @property int $id
 * @property string $device_name 设备名称
 * @property int $pid 产品ID
 * @property string $iccid 卡号
 * @property int $expiration_time 到期时间
 * @property string $number 产品序列号
 * @property string $device_id 物联网平台id
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Device extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_ali_device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_name', 'pid'], 'required'],
            [['pid', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['device_name'], 'string', 'max' => 50],
            [['iccid'], 'string', 'max' => 30],
            [['number'], 'string', 'max' => 100],
            [['device_id'], 'string', 'max' => 255],
            ['expiration_time', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_name' => 'Device Name',
            'pid' => 'Pid',
            'iccid' => 'Iccid',
            'expiration_time' => 'Expiration Time',
            'number' => 'Number',
            'device_id' => 'Device ID',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /*
    * 设备24小时内有无数据
    */

    public function getDeviceStatus()
    {
        return DeviceValue::find()->where(['pid' => $this->id])
            ->andWhere(['between', 'event_time', strtotime('-1 day'), time()])
            ->exists() ? '<span class="label label-info">在线</span>' : '<span class="label label-warning">离线</span>';
    }

     /*
    *对应产品
    *@return Array
    */
    public function getProduct(){
    	return $this->hasOne(Product::className(),['id' => 'pid']);
    }
}

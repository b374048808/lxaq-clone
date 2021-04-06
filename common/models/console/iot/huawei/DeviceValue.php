<?php

namespace common\models\console\iot\huawei;

use Yii;

/**
 * This is the model class for table "rf_lx_iot_huawei_device_value".
 *
 * @property int $id
 * @property int $pid 设备ID
 * @property array $value 数据
 * @property int $status 状态
 * @property int $event_time 云端时间
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class DeviceValue extends \common\models\base\BaseModel
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
            [['pid', 'value'], 'required'],
            [['pid', 'status', 'event_time', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'safe'],
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
            'value' => 'Value',
            'status' => 'Status',
            'event_time' => 'Event Time',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

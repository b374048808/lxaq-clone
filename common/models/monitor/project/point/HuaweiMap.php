<?php

namespace common\models\monitor\project\point;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_device_huawei_map".
 *
 * @property int $id
 * @property int $device_id 设备id
 * @property int $point_id 监测点id
 * @property int $install_time 安装时间
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class HuaweiMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_device_huawei_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'point_id'], 'required'],
            [['device_id', 'point_id', 'install_time', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => 'Device ID',
            'point_id' => 'Point ID',
            'install_time' => 'Install Time',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

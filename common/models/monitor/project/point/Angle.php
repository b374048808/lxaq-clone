<?php

namespace common\models\monitor\project\point;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_angle".
 *
 * @property int $id
 * @property int $pid 监测点id
 * @property int $value_id 原始数据id
 * @property string $level 水平距离mm
 * @property string $vertical 垂直距离mm
 * @property string $value 倾斜率
 * @property int $event_time 时间
 * @property int $warn 预警等级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Angle extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_angle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid','type', 'value_id', 'warn', 'status', 'created_at', 'updated_at'], 'integer'],
            [['level', 'vertical', 'value'], 'number'],
            ['event_time','safe']
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
            'type'  => '数据类型',
            'value_id' => 'Value ID',
            'level' => 'Level',
            'vertical' => 'Vertical',
            'value' => 'Value',
            'event_time' => 'Event Time',
            'warn' => 'Warn',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

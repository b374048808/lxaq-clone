<?php

namespace common\models\monitor\project\point;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_sink".
 *
 * @property int $id
 * @property int $pid 监测点id
 * @property int $value_id 原始数据id
 * @property string $value 沉降
 * @property int $event_time 时间
 * @property int $warn
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Sink extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_sink';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'value_id', 'event_time', 'warn', 'status', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'number'],
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
            'value_id' => 'Value ID',
            'value' => 'Value',
            'event_time' => 'Event Time',
            'warn' => 'Warn',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

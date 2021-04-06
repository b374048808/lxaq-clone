<?php

namespace common\models\monitor\project\point;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_temperature".
 *
 * @property int $id
 * @property int $pid 监测点id
 * @property int $value_id 原始数据id
 * @property string $value 摄氏度℃
 * @property int $event_time 时间
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Temperature extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_temperature';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'value_id', 'event_time', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

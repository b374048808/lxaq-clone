<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-26 15:00:05
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-26 15:20:21
 * @Description: 
 */

namespace common\models\monitor\project\item;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_item_config".
 *
 * @property int $id 主键
 * @property int $pid 绑定项目
 * @property int $day 周期天数
 * @property int $is_device 动态监测[0:人工监测,1:动态监测]
 * @property int $device_num 要求设备数量
 * @property array $type 类型
 * @property string $remark 备注
 */
class Config extends yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item_config';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'day', 'is_device', 'device_num'], 'integer'],
            [['type'], 'safe'],
            [['remark'], 'string', 'max' => 255],
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
            'day' => 'Day',
            'is_device' => 'Is Device',
            'device_num' => 'Device Num',
            'type' => 'Type',
            'remark' => 'Remark',
        ];
    }
}

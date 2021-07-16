<?php

namespace addons\RfMonitor\common\models;

use Yii;

/**
 * This is the model class for table "rf_addon_monitor_angle".
 *
 * @property int $id
 * @property int $pid 房子id
 * @property string $title 点位
 * @property string $level_first 第一次水平距离mm
 * @property string $level_second 第二次水平距离mm
 * @property string $level 水平距离mm
 * @property string $vertical_first 第一次垂直距离mm
 * @property string $vertical_second 第二次垂直距离mm
 * @property string $vertical 垂直距离mm
 * @property string $value 倾斜率
 * @property int $news 状态[0:未选择;1:北]
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
        return 'rf_addon_monitor_angle';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'news', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'required'],
            [['level_first', 'level_second', 'level', 'vertical_first', 'vertical_second', 'vertical', 'value'], 'number'],
            [['title'], 'string', 'max' => 50],
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
            'title' => 'Title',
            'level_first' => '首次水平',
            'level_second' => '第二水平',
            'level' => '水平差距',
            'vertical_first' => '首次垂直',
            'vertical_second' => '第二垂直',
            'vertical' => '垂直差距',
            'value' => '倾斜率',
            'news' => '方向',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}

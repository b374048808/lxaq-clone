<?php

namespace common\models\monitor\rule;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_rule_item".
 *
 * @property int $id
 * @property int $pid 规则
 * @property int $type 类型
 * @property int $judge 判断
 * @property string $value 数值
 * @property string $color 代表颜色
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class RuleItem extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_rule_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'type', 'judge', 'sort', 'warn', 'status', 'created_at', 'updated_at'], 'integer'],
            [['value'], 'number'],
            [['description'], 'string', 'max' => 140],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '规则',
            'type' => '类型',
            'judge' => '判断',
            'value' => '数值',
            'warn' => '报警等级',
            'description' => '描述',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}

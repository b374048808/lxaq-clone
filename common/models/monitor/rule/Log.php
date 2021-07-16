<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 08:59:09
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-07 14:38:02
 * @Description: 
 */

namespace common\models\monitor\rule;

use common\models\monitor\project\House;
use common\models\monitor\project\Point;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_rule_log".
 *
 * @property int $id
 * @property int $item_id 触发器id
 * @property int $point_id 房屋id
 * @property int $value_id 数据id
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Log extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_rule_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'point_id', 'value'], 'required'],
            [['value'], 'number'],
            [['item_id', 'point_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'item_id' => 'Item ID',
            'value' => '数据',
            'description' => '说明',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '触发时间',
            'updated_at' => 'Updated At',
        ];
    }

    public function getHouse()
    {
        return $this->hasOne(House::class,['id' => 'pid'])->viaTable(Point::tableName(),['id' => 'point_id']);
    }

    public function getItem()
    {
        return $this->hasOne(RuleItem::class,['id' => 'item_id']);
    }

    public function getPoint()
    {
        return $this->hasOne(Point::class,['id' => 'point_id']);
    }

}

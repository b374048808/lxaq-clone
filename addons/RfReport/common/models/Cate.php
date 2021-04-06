<?php

namespace addons\RfReport\common\models;

use Yii;

/**
 * This is the model class for table "rf_addon_report_cate".
 *
 * @property int $id 主键
 * @property int $merchant_id 商户id
 * @property string $title 标题
 * @property int $sort 排序
 * @property int $level 级别
 * @property int $pid 上级id
 * @property string $tree 树
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Cate extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_addon_report_cate';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['merchant_id', 'sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['tree'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'merchant_id' => '商户ID',
            'title' => '标题',
            'sort' => '排序',
            'level' => '等级',
            'pid' => '父类',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}

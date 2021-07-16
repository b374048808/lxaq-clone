<?php

namespace addons\RfMonitor\common\models;

use Yii;

/**
 * This is the model class for table "rf_addon_monitor_house".
 *
 * @property int $id
 * @property string $title 标题
 * @property string $hold 户主
 * @property array $covers 监测点分布图
 * @property string $description 描述
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class House extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_addon_monitor_house';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'hold'], 'required'],
            [['covers'], 'safe'],
            [['status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'hold'], 'string', 'max' => 50],
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
            'title' => '标题',
            'hold' => '户主',
            'covers' => '点位',
            'description' => '备注',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}

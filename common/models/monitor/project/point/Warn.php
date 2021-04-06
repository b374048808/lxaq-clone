<?php

namespace common\models\monitor\project\point;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_warn".
 *
 * @property int $id
 * @property int $point_id 监测点ID
 * @property int $cate_id 分类
 * @property int $data_id 数据ID
 * @property string $description 描述
 * @property int $warn 报警等级
 * @property int $deal 处理方式
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Warn extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_warn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['point_id', 'cate_id', 'data_id', 'warn', 'deal', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'point_id' => 'Point ID',
            'cate_id' => 'Cate ID',
            'data_id' => 'Data ID',
            'description' => 'Description',
            'warn' => 'Warn',
            'deal' => 'Deal',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

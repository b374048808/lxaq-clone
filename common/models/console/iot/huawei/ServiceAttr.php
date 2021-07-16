<?php

namespace common\models\console\iot\huawei;

use Yii;

/**
 * This is the model class for table "rf_lx_iot_huawei_product_service_attr".
 *
 * @property int $id
 * @property int $pid 对应产品
 * @property string $title 属性名称
 * @property string $string 字符
 * @property int $type 类型
 * @property string $unit 单位
 * @property int $sort 排序
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class ServiceAttr extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_huawei_product_service_attr';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'required'],
            [['title', 'unit'], 'string', 'max' => 50],
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
            'pid' => 'Pid',
            'title' => '属性名称',
            'type' => '类型',
            'unit' => '单位',
            'sort' => '排序',
            'description' => '描述',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
}

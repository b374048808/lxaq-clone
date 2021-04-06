<?php

namespace common\models\console\iot\huawei;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_iot_huawei_product".
 *
 * @property int $id
 * @property string $name 产品名称
 * @property string $type 设备类型
 * @property string $product_key 产品id
 * @property string $producers 产家名称
 * @property string $cover 封面
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Product extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_huawei_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'type', 'product_key', 'producers'], 'string', 'max' => 50],
            [['cover'], 'string', 'max' => 150],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'type' => 'Type',
            'product_key' => 'Product Key',
            'producers' => 'Producers',
            'cover' => 'Cover',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return array
     */
    public static function getMapList(){
        $data = self::find()->where(['>=','status','StatusEnum::DISABLED'])->asArray()->all();
        return ArrayHelper::map($data,'id','name');
    }
}

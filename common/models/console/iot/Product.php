<?php

namespace common\models\iot;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_iot_product".
 *
 * @property int $id
 * @property string $name 设备名称
 * @property int $cate_id 分类[阿里、华为]
 * @property string $type 型号
 * @property string $product_key 产品key
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
        return 'rf_lx_iot_product';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['cate_id', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['name', 'type', 'product_key'], 'string', 'max' => 50],
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
            'cate_id' => 'Cate ID',
            'type' => 'Type',
            'product_key' => 'Product Key',
            'cover' => 'Cover',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    // 产品数组 key => name
    public static function getMapList(){
        $data = self::find()->where(['>=','status','StatusEnum::DISABLED'])->asArray()->all();
        return ArrayHelper::map($data,'id','name');
    }
}

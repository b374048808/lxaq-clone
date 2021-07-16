<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 10:23:18
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 10:47:47
 * @Description: 产品
 */

namespace common\models\console\iot\ali;

use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_iot_ali_product".
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
        return 'rf_lx_iot_ali_product';
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
            'name' => '设备名称',
            'type' => '类型',
            'product_key' => '产品秘钥',
            'producers' => '厂家',
            'cover' => '封面',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 产品列表
     * 
     * @return array
     */
    public static function getMapList(){
        $data = self::find()
            ->where(['>=','status','StatusEnum::DISABLED'])
            ->asArray()
            ->all();
        return ArrayHelper::map($data,'id','name');
    }
}

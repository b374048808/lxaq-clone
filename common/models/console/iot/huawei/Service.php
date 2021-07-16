<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-14 14:04:11
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 10:52:45
 * @Description: 服务
 */

namespace common\models\console\iot\huawei;

use Yii;

/**
 * This is the model class for table "rf_lx_iot_huawei_product_service".
 *
 * @property int $id
 * @property int $pid 对应产品
 * @property string $title 属性名称
 * @property string $string 字符
 * @property int $sort 排序
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Service extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_huawei_product_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'required'],
            [['title', 'string'], 'string', 'max' => 50],
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
            'title' => '标题',
            'string' => '字符',
            'sort' => '排序',
            'description' => '描述',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 关联属性
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAttr()
    {
        return $this->hasMany(ServiceAttr::class,['pid' => 'id']);
    }


    /**
     * 关联属性最新数据
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewAttr()
    {
        return $this->hasOne(Value::class,['serviceType' => 'title'])
            ->orderBy('event_time desc');
    }
}

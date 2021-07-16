<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:34:26
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 09:21:56
 * @Description: 
 */

namespace common\models\sim\vlist;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use Yii;

/**
 * This is the model class for table "rf_lx_sim_card".
 *
 * @property int $id
 * @property int $type 类型
 * @property int $package 套餐
 * @property int $operator 运营商
 * @property int $iccid 卡号
 * @property int $active_time 激活日期
 * @property int $expiration_time 到期时间
 * @property string $supplier 供应商
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Card extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_sim_card';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type', 'package', 'operator', 'iccid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['iccid'], 'required'],
            [['supplier'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 140],
            [['active_time','expiration_time'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'type' => '类型',
            'package' => '套餐',
            'operator' => '运营商',
            'iccid' => '卡号',
            'active_time' => '激活时间',
            'expiration_time' => '到期时间',
            'supplier' => '供应商',
            'description' => '备注',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }


    public static function getMap()
    {
        $model = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return ArrayHelper::map($model, 'id','iccid');
    }


}

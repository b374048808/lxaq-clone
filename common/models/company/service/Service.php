<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-24 14:10:09
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 16:28:42
 * @Description: 
 */

namespace common\models\company\service;

use common\enums\StatusEnum;
use Yii;
use common\models\worker\Worker;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_service".
 *
 * @property int $id
 * @property int $pid 模版id
 * @property string $title 标题
 * @property int $manager_id 管理id
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Service extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'title'], 'required'],
            [['pid', 'manager_id','push_id','is_system', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '模版',
            'title' => '标题',
            'manager_id' => '负责人',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {           
           
            
        }

        return parent::beforeSave($insert);
    }

    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(Worker::class,['id' => 'manager_id']);
    }
}

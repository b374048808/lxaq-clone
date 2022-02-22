<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-24 14:10:43
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-25 10:37:44
 * @Description: 
 */

namespace common\models\company\service;

use common\models\worker\Worker;
use Yii;

/**
 * This is the model class for table "rf_lx_service_steps".
 *
 * @property int $id
 * @property string $title 标题
 * @property int $pid 模版id
 * @property int $push_id 默认推送人id
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Steps extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_service_steps';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'pid'], 'required'],
            [['pid', 'push_id','sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 50],
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
            'pid' => 'Pid',
            'sort' => '排序',
            'description' => '描述',
            'push_id' => '负责人',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
    

    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(Worker::class,['id' => 'push_id']);
    }
}

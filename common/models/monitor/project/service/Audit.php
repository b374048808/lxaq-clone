<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-21 16:33:53
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 15:44:11
 * @Description: 
 */

namespace common\models\monitor\project\service;

use common\models\monitor\project\Item;
use common\models\worker\Worker;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_service_audit".
 *
 * @property int $id
 * @property int $user_id 审核人员
 * @property int $pid 任务id
 * @property string $remark 备注
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Audit extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_service_audit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id','verify', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
            [['remark'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 140],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [ 
            'id' => 'ID',
            'user_id' => '人员',
            'pid' => '任务',
            'ip' => 'ip',
            'verify'    => '审核',
            'remark' => '说明',
            'description' => '备注',
            'status' => '状态',
            'created_at' => '审核时间',
            'updated_at' => 'Updated At',
        ];
    }

    public function getUser(){
        return $this->hasOne(Worker::class,['id' => 'user_id']);
    }

    public function getItem(){
        return $this->hasOne(Item::class,['id' => 'pid'])
            ->viaTable(Service::tableName(),['id' => 'pid']);
    }

    public function getMember(){
        return $this->hasOne(Worker::class,['id' => 'manager'])
            ->viaTable(Service::tableName(),['id' => 'pid']);
    }
}

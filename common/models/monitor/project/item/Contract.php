<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-09 09:06:47
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-01 11:54:12
 * @Description: 
 */

namespace common\models\monitor\project\item;

use common\models\monitor\project\Item;
use Yii;
use common\models\worker\Worker;

/**
 * This is the model class for table "rf_lx_monitor_item_contract".
 *
 * @property int $id
 * @property int $user_id 上传人员
 * @property int $manager 经办人
 * @property int $pid 项目
 * @property string $money 金额
 * @property int $event_time 签约日期
 * @property int $audit 审核状态
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property array $file 文件
 */
class Contract extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item_contract';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['file', 'manager'], 'required'],
            [['user_id', 'manager', 'pid',  'audit', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
            [['money'], 'double'],
            [['event_time','file'], 'safe'],
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
            'user_id' => '操作人员',
            'manager' => '经办人',
            'pid' => '合同',
            'money' => '金额',
            'event_time' => '上传日期',
            'audit' => '审核',
            'description' => '描述',
            'status' => '状态',
            'created_at' => '创建日期',
            'updated_at' => '修改日期',
            'file' => '附件',
        ];
    }


    public function getMember(){
        return $this->hasOne(Worker::class,['id' => 'user_id']);
    }

    public function getManager(){
        return $this->hasOne(Worker::class,['id' => 'manager']);
    } 

    public function getItem(){
        return $this->hasOne(Item::class,['pid' => 'id']);
    }

}

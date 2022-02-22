<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-30 09:18:43
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 10:50:33
 * @Description: 
 */

namespace common\models\worker;

use Yii;

/**
 * This is the model class for table "rf_worker_mini_message_log".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property string $open_id 用户编号
 * @property int $pid 消息id
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property int $use_time 使用时间
 * @property string $ip ip地址
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MiniMessageLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_worker_mini_message_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id','error_code', 'pid', 'use_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
            [['error_data'], 'string'],
            [['error_msg'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 30],
            ['message_data','safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '触发用户',
            'message_data' => '发送内容',
            'pid' => '消息',
            'error_msg' => '消息编号',
            'error_code' => '返回代码',
            'error_data' => '消息内容经',
            'use_time' => '触发时间',
            'ip' => 'IP地址',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public  function getMember(){
        return $this->hasOne(Worker::class,['id' => 'member_id']);
    }

    public function getMessage(){
        return $this->hasOne(MiniMessage::class,['id' => 'pid']);
    }

}

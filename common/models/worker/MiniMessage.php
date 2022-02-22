<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-25 12:03:40
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 10:30:51
 * @Description: 
 */

namespace common\models\worker;

use Yii;

/**
 * This is the model class for table "rf_worker_mini_message".
 *
 * @property int $id 主键
 * @property string $template_id 订阅消息模版
 * @property string $open_id 用户
 * @property int $target_id 目标id
 * @property string $action 动作
 * @property int $is_read 是否已读 1已读
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class MiniMessage extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_worker_mini_message';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['open_id'], 'required'],
            [['target_id', 'is_read','member_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['open_id'], 'string', 'max' => 140],
            [['action','target_type'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'open_id' => '小程序端编号',
            'target_id' => '目标ID',
            'action' => '动作',
            'member_id' => '用户',
            'is_read' => '已阅',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getMember(){
        return $this->hasOne(Worker::class,['id' => 'member_id']);
    }
}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-08 15:25:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-15 10:55:27
 * @Description: 
 */

namespace common\models\monitor\project\item;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_item_audit".
 *
 * @property int $id
 * @property int $user_id 审核人员
 * @property int $pid 物联卡
 * @property int $audit 审核状态
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class AuditLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item_audit';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
            [['description'], 'string', 'max' => 140],
            [['remark'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'pid' => 'Pid',
            'audit' => 'Audit',
            'description' => 'Description',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

}

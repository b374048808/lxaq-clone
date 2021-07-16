<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-30 11:48:12
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 14:41:58
 * @Description: 
 */

namespace common\models\monitor\project\log;

use Yii;
use common\models\backend\Member;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Value;

/**
 * This is the model class for table "rf_lx_monitor_point_value_log".
 *
 * @property int $id
 * @property int $user_id 管理员
 * @property int $pid 数据
 * @property int $type 数据类型[1:动态,2:人工]
 * @property string $value 数据
 * @property int $event_time 时间
 * @property int $warn 预警等级
 * @property int $state 状态，0关闭，1开启，2审核中
 * @property string $remark 日志备注
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class ValueLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_value_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pid', 'type', 'warn', 'state', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
            [['value'], 'number'],
            [['remark'], 'string', 'max' => 1000],
            ['event_time','safe']
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
            'type' => 'Type',
            'value' => 'Value',
            'event_time' => 'Event Time',
            'warn' => 'Warn',
            'state' => 'State',
            'remark' => 'Remark',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->identity->id;

        return parent::beforeSave($insert);
    }


    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Member::class,['id' => 'user_id']);
    }

    /**
     * 关联监测点位
     *
     * @return \yii\db\ActiveQuery
     */
    public function getOnValue()
    {
        return $this->hasOne(Value::class,['id' => 'pid']);
    }

    /**
     * 关联监测点位
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoint()
    {
        return $this->hasOne(Point::class,['id' => 'pid'])
            ->viaTable(Value::tableName(),['id' => 'pid']);
    }


}

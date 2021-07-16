<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-13 14:02:15
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 15:44:28
 * @Description: 
 */

namespace common\models\monitor\project\log;

use common\models\monitor\project\House;
use common\models\monitor\project\Point;
use common\models\backend\Member;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_warn_log".
 *
 * @property int $id
 * @property int $manage_id 管理id
 * @property int $pid 报警id
 * @property int $data_id 数据ID
 * @property int $warn 预警等级
 * @property string $remark 日志备注
 * @property int $state 处理方式
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class WarnLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_warn_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['manage_id', 'pid', 'warn', 'state', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid', 'warn'], 'required'],
            [['remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'manage_id' => 'Manage ID',
            'pid' => 'Pid',
            'remark' => '描述',
            'warn' => '报警等级',
            'state' => '处理方式',
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
        $this->manage_id = Yii::$app->user->id;

        return parent::beforeSave($insert);
    }

    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Member::class,['id' => 'manage_id']);
    }

    
    public function getHouse(){
        return $this->hasOne(House::class,['id' => 'pid'])
            ->viaTable(Point::tableName(),['id' => 'pid']);
    }

    public function getPoint()
    {
        return $this->hasOne(Point::class,['id' => 'pid']);
    }
}

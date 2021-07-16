<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 15:24:22
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 16:49:46
 * @Description: 
 */

namespace common\models\sim\renewal;

use common\models\backend\Member;
use common\models\sim\vlist\Card;
use Yii;

/**
 * This is the model class for table "rf_lx_sim_renewal_log".
 *
 * @property int $id
 * @property int $user_id 操作人员
 * @property int $pid 物联卡
 * @property int $day 天数
 * @property int $expiration_time 到期时间
 * @property string $description 描述
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Log extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_sim_renewal_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pid', 'day', 'expiration_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['pid'], 'required'],
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
            'user_id' => '管理员',
            'pid' => 'Pid',
            'day' => '天数',
            'expiration_time' => '续期日期',
            'description' => '备注',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => 'Updated At',
        ];
    }


     /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->user_id = Yii::$app->user->id;
            $model = Card::findOne($this->pid);
            $model->expiration_time = $this->expiration_time;
            $model->save();
            
        }

        return parent::beforeSave($insert);
    }

    public function getUser()
    {
        return $this->hasOne(Member::class,['id' => 'user_id']);
    }


    public function getCard()
    {
        return $this->hasOne(Card::class,['id' => 'pid']);
    }
}

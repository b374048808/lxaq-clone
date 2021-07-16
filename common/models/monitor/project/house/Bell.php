<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-02 09:53:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-02 10:33:49
 * @Description: 
 */

namespace common\models\monitor\project\house;

use common\models\monitor\project\House;
use common\models\backend\Member;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_house_bell".
 *
 * @property int $id
 * @property int $pid 房屋
 * @property int $type 类型
 * @property int $event_time 提醒时间
 * @property string $description 描述
 * @property int $state 处理方式
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Bell extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_house_bell';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'type', 'user_id', 'state', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 140],
            ['event_time', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id'   => '创建人员',
            'pid' => '房屋',
            'type' => '类型',
            'event_time' => '日期',
            'description' => '描述',
            'state' => '处理',
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
            $this->user_id = Yii::$app->user->identity->id;
        }

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



    public function getHouse()
    {
        return $this->hasOne(House::class,['id' => 'pid']);
    }
}

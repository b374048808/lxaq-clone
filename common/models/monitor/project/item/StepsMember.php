<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-12 14:00:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 15:52:37
 * @Description: 
 */

namespace common\models\monitor\project\item;

use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use Yii;
use common\models\worker\Worker;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "rf_lx_monitor_item_steps_member".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property int $step_id 步骤id
 * @property string $description 描述
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class StepsMember extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item_steps_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id'], 'unique', 'filter' => function(ActiveQuery $query) {
                return $query->andWhere(['step_id' => $this->step_id]);
            },'message' => '此用户的该权限已存在'],
            [['member_id', 'step_id', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'member_id' => '用户',
            'step_id' => '步骤',
            'description' => '备注',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    public function getMember(){
        return $this->hasOne(Worker::class,['id' => 'member_id']);
    }

    public static function getMemberColumn($steps = ''){
        $model = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['step_id' => $steps])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'member_id', $keepKeys = true) ?:[];
    }

    public static function getRole($member_id,$steps){
        return self::find()
            ->where(['member_id' => $member_id])
            ->andWhere(['step_id' => $steps])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->exists();
    }

}

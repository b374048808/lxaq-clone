<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-09 15:51:32
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 17:29:29
 * @Description: 
 */

namespace common\models\company\service;

use common\enums\company\StepStatusEnum;
use Yii;
use common\models\worker\Worker;

/**
 * This is the model class for table "rf_lx_service_steps_on".
 *
 * @property int $id
 * @property int $pid 任务
 * @property int $step_id 步骤
 * @property int $push_id 推送人id
 * @property string $description 描述
 * @property string $feedback 反馈说明
 * @property int $status 状态[0:驳回;1:待完成;2:完成]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property array $file 附件
 */
class StepsOn extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_service_steps_on';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'step_id', 'push_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['images'], 'safe'],
            [['description'], 'string', 'max' => 140],
            [['feedback','file'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'step_id' => 'Step ID',
            'push_id' => 'Push ID',
            'description' => 'Description',
            'feedback' => 'Feedback',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'file' => 'File',
        ];
    }

    public function getStep(){
        return $this->hasOne(Steps::class,['id' => 'step_id']);
    }


    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(Worker::class,['id' => 'push_id']);
    }
    
    /**
     * {@inheritdoc}
     */
    public static function getBar($pid)
    {
        $model = self::find()
            ->where(['pid' => $pid])
            ->andWhere(['>','status',StepStatusEnum::DELETE]);            
        $allCount = $model->count();
        $endCount = $model->andWhere(['status' => StepStatusEnum::ENABLED])->count();
        return $allCount
            ?round($endCount/$allCount,2)*100
            :0;
    }

    /**
     * @param $ground_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addValue($pid, $steps)
    {
        // 删除原有标签关联;
        if ($pid && !empty($steps)) {
            $data = [];

            foreach ($steps as $v) {
                $data[] = [$pid,$v['id'],$v['push_id'],$v['description']];
            }

            $field = ['pid', 'step_id','push_id','description'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

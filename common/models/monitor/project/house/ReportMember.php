<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-25 16:24:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-23 15:29:58
 * @Description: 
 */

namespace common\models\monitor\project\house;

use common\models\worker\Worker;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_house_report_verify".
 *
 * @property int $id
 * @property int $pid 报告
 * @property int $member_id 审核用户id
 * @property int $verify 审核
 * @property string $description 描述
 * @property string $remark 备注
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class ReportMember extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_house_report_member';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'member_id', 'verify', 'status', 'created_at', 'updated_at'], 'integer'],
            [['verify'], 'required'],
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
            'pid' => 'Pid',
            'member_id' => 'Member ID',
            'verify' => '审核',
            'description' => '备注',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getMember(){
        return $this->hasOne(Worker::class,['id' => 'member_id'])->select(['id','realname']);
    }

    /**
     * @param $item_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addDatas($pid, $ids)
    {
        // 删除原有标签关联;
        if ($pid && !empty($ids)) {
            $data = [];

            foreach ($ids as $v) {
                $data[] = [$v, $pid];
            }

            $field = ['member_id', 'pid'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

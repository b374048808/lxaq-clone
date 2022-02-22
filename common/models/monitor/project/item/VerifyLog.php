<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-11 09:54:47
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-27 10:55:09
 * @Description: 
 */

namespace common\models\monitor\project\item;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_item_verify_log".
 *
 * @property int $id
 * @property int $member_id 用户id
 * @property string $description 描述
 * @property string $remark 备注
 * @property string $ip ip地址
 * @property int $map_id 关联id
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class VerifyLog extends \common\models\base\BaseModel
{
    public $audit;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item_verify_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['audit'], 'required', 'on' => ['backendSteps']],
            [['description'], 'string', 'max' => 140,'on' => ['backendSteps']],
            [['remark'], 'string', 'max' => 200,'on' => ['backendSteps']],
            [['member_id','audit','verify', 'map_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 140],
            [['remark'], 'string', 'max' => 200],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'description' => 'Description',
            'remark' => 'Remark',
            'ip' => 'Ip',
            'map_id' => 'Map ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * 场景
     *
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['backendSteps'] = ['description','audit'];

        return $scenarios;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            // $this->app_id = Yii::$app->id;
        }

        return parent::beforeSave($insert);
    }

}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-02 10:46:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-02 11:29:39
 * @Description: 
 */

namespace common\models\monitor\project\house;

use common\models\monitor\project\House;
use common\models\backend\Member;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_house_report".
 *
 * @property int $id
 * @property int $user_id 用户
 * @property int $pid 房屋
 * @property int $type 类型
 * @property array $files 文件
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Report extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_house_report';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pid', 'type', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['files'], 'safe'],
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
            'user_id' => '用户',
            'pid' => '房屋',
            'type' => '类型',
            'files' => '文件',
            'description' => '描述',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '上传时间',
            'updated_at' => '修改时间',
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

    public function getHouse(){
        return $this->hasOne(House::class,['id' => 'pid']);
    }
}

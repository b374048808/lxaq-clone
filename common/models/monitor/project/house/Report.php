<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-02 10:46:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 14:15:42
 * @Description: 
 */

namespace common\models\monitor\project\house;

use common\models\monitor\project\House;
use Yii;
use common\models\worker\Worker;

/**
 * This is the model class for table "rf_lx_monitor_house_report".
 *
 * @property int $id
 * @property int $pid 房屋
 * @property int $manager_id 管理员
 * @property int $user_id 用户
 * @property int $is_admin 管理员上传[0:否;1是]
 * @property int $type 类型
 * @property string $file 文件
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
            [['file','description'], 'required'],
            [['pid', 'user_id', 'verify', 'type', 'sort','verify_member', 'status', 'created_at', 'updated_at'], 'integer'],
            [['file'], 'string', 'max' => 250],
            [['description'], 'string', 'max' => 140],
            [['number','file_name'], 'string', 'max' => 50],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '房屋',
            'number' => '编号',
            'user_id' => 'User ID',
            'verify'  => '核实',
            'verify_member' => '审核用户',
            'type' => '类型',
            'file_name' => '文件名',
            'file' => '文件',
            'description' => '结论',
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
        return parent::beforeSave($insert);
    }

    public function getVerifyMember(){
        return $this->hasOne(Worker::class,['id' => 'verify_member']);
    }

    public function getVerifyMemberList(){
        return $this->hasMany(ReportMember::class,['pid' => 'id'])->with('member');
    }

    
    public function getHouse(){
        return $this->hasOne(House::class,['id' => 'pid']);
    }

    public function getLog(){
         return $this->hasMany(ReportVerify::class,['pid' => 'id']);
    }


    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Worker::class,['id' => 'user_id']);
    }

}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-16 10:54:27
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-07 15:42:53
 * @Description: 
 */

namespace common\models\monitor\project\service;

use Yii;
use common\models\worker\Worker;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\models\monitor\project\House;
use common\models\monitor\project\Item;
use common\helpers\RegularHelper;
use common\models\monitor\project\item\VerifyLog;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "rf_lx_monitor_service".
 *
 * @property int $id
 * @property int $pid 项目
 * @property int $manager 负责人
 * @property array $join 参加人员
 * @property int $end_time 结束时间
 * @property int $start_time 开始时间
 * @property int $is_admin 是否后台发布
 * @property int $publisher 发布者
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Service extends \common\models\base\BaseModel
{

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_service';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [ 
            [['pid', 'manager','audit', 'publisher', 'sort', 'status', 'created_at', 'updated_at','cate_id'], 'integer'],
            [['manager'], 'required'],
            [['contact'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 20],
            [['end_time', 'start_time'], 'safe'],
            [['description'], 'string', 'max' => 140],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(),'message' => '不是一个有效的手机号码'],
        ];
    }

    /**
     * {@inheritdoc}
     */ 
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '项目',
            'cate_id' => '类别',
            'contact' => '联系人',
            'mobile' => '联系方式',
            'manager' => '负责人',
            'end_time' => '截止时间',
            'start_time' => '开始时间',
            'publisher' => '发布者',
            'description' => '描述',
            'audit' => '审核',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }


    /**
     * 关联分类
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCate()
    {
        return $this->hasOne(ServiceCate::class,['id' => 'cate_id']);
    }

    public function getMap(){
        return $this->hasMany(Map::class,['pid' => 'id'])->andWhere(['status' => StatusEnum::ENABLED]);
    }

    /**
     * 发布者
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser(){
        return $this->hasOne(Worker::class,['id' => 'publisher']);
    }

    public function getMember(){
        return $this->hasOne(Worker::class,['id' => 'manager']);
    }


    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getItem()
    {
        return $this->hasOne(Item::class,['id' => 'pid']);
    }


    /**
     * 关联项目审核信息
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewVerifyLog(){
        return $this->hasOne(VerifyLog::class,['map_id' =>'id'])->orderBy('id desc')
            ->viaTable(Item::tableName(),['id' => 'pid']);
    }

    public function getNewAuditLog(){
        return $this->hasOne(Audit::class,['pid' => 'id'])->andWhere(['status' => StatusEnum::ENABLED])->andWhere(['or',['verify' => VerifyEnum::PASS],['verify' => VerifyEnum::OUT]])->orderBy('id desc');
    }

    public function getAuditLog(){
        return $this->hasMany(Audit::class,['pid' => 'id']);
    }

    

    public function getHouse()
    {
        return $this->hasMany(House::class,['id' => 'map_id'])
            ->viaTable(Map::tableName(),['pid' => 'id']);
    }


    
}

<?php

namespace common\models\monitor\project;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\worker\Worker;
use common\models\monitor\project\item\Contract;
use common\models\monitor\project\item\VerifyLog;
use common\models\monitor\project\item\Config;
use common\helpers\RegularHelper;
use common\models\monitor\project\service\Service;

/**
 * This is the model class for table "rf_lx_monitor_item".
 *
 * @property int $id
 * @property string $title 项目照片
 * @property string $belonger 归属人
 * @property string $contact 联系人
 * @property string $mobile 联系方式
 * @property array $type
 * @property int $end_time 结束时间
 * @property int $start_time 开始时间
 * @property string $description 描述
 * @property string $demand 项目需求
 * @property string $survey 项目概况
 * @property int $sort 优先级
 * @property int $audit 审核状态
 * @property array $collection
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property array $file 附件
 * @property int $device_num 要求设备数量
 * @property int $user_id
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property string $address 默认地址
 * @property int $steps 步骤
 * @property string $remark 备注
 * @property string $job_desc 作业说明
 * @property string $money 金额
 * @property string $entrust 委托方
 * @property string $number 编号
 * @property int $struct_type 结构类型
 */
class Item extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'],'required'],
            [['type','sort', 'audit', 'status', 'created_at', 'updated_at', 'user_id', 'province_id', 'city_id', 'area_id', 'steps', 'struct_type'], 'integer'],
            [['money'], 'number'],
            [['title', 'number'], 'string', 'max' => 50],
            [['belonger', 'contact'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 20],
            [['description', 'demand', 'survey', 'entrust'], 'string', 'max' => 140],
            [['address'], 'string', 'max' => 100],
            [['remark'], 'string', 'max' => 255],
            [['start_time','end_time','event_time','images', 'collection', 'file'],'safe'],
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
            'title' => '项目名称',
            'belonger' => '归属人',
            'contact' => '联系人',
            'mobile' => '联系方式',
            'type' => '类型',
            'end_time' => '计划截止时间',
            'start_time' => '计划进场时间',
            'event_time' => '立项时间',
            'description' => '说明',
            'demand' => '项目需求',
            'survey' => '项目概述',
            'sort' => '排序',
            'audit' => '审核',
            'collection' => '收款',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'file' => '附件',
            'user_id' => '创建人员',
            'province_id' => '省份',
            'city_id' => '城市',
            'area_id' => '区',
            'address' => '默认地址',
            'steps' => '步骤',
            'remark' => '备注',
            'money' => '预计金额',
            'entrust' => '委托方',
            'number' => '编号',
            'struct_type' => '结构类型',
        ];
    }

    
/**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {           
            
        }

        return parent::beforeSave($insert);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDropDown()
    {
        $models = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('id desc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($models);
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }

    public function getConfig(){
        return $this->hasOne(Config::class,['pid' => 'id']);
    }

    public function getUser(){
        return $this->hasOne(Worker::class,['id' => 'user_id']);
    }

    public function getHouse(){
        return $this->hasMany(House::class,['pid' => 'id']);
    }


    public function getAuditLog(){
        return $this->hasMany(VerifyLog::class,['map_id' => 'id'])->andWhere(['status' => StatusEnum::ENABLED]);
    }

    public function getContracts()
    {
        return $this->hasMany(Contract::class,['pid' => 'id']);
    }

    public function getNewVerifyLog(){
        return $this->hasOne(VerifyLog::class,['map_id' =>'id'])->orderBy('id desc');
    }

    public function getServices(){
        return $this->hasMany(Service::class,['pid' => 'id']);
    }

    public function getContract(){
        return $this->hasMany(Contract::class,['pid' => 'id']);
    }
}

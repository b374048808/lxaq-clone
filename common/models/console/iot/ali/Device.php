<?php

namespace common\models\console\iot\ali;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_iot_ali_device".
 *
 * @property int $id
 * @property string $device_name 设备名称
 * @property int $pid 产品ID
 * @property string $iccid 卡号
 * @property int $expiration_time 到期时间
 * @property string $number 产品序列号
 * @property string $device_id 物联网平台id
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Device extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_ali_device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id','number'],'unique'],
            [['pid', 'device_id'], 'required'],
            [['pid', 'sort', 'status', 'created_at', 'updated_at','card_id'], 'integer'],
            ['start_data','number'],
            [['device_name'], 'string', 'max' => 50],
            [['number'], 'string', 'max' => 100],
            [['device_id'], 'string', 'max' => 255],
            ['expiration_time', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_name' => '设备名',
            'pid' => '设备',
            'card_id' => '卡号',
            'start_data' => "起始值",
            'expiration_time' => '截止时间',
            'number' => '编号',
            'device_id' => '设备ID',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 设备下拉列表
     * 
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDropDown()
    {
        $models = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc,created_at asc')
            ->asArray()
            ->all();

        return ArrayHelper::map($models, 'id', 'number');
    }

    /*
    * 设备状态(24小时内有无数据)
    * @return html
    */
    public function getDeviceStatus()
    {
        return Value::find()
                ->where(['pid' => $this->id])
                ->andWhere(['between', 'event_time', strtotime('-1 day'), time()])
                ->exists() 
            ? '<span class="label label-info">在线</span>' 
            : '<span class="label label-warning">离线</span>';
    }

    public function getNewValue()
    {
        return $this->hasOne(Value::class,['pid' => 'id'])
            ->orderBy('event_time desc');
    }

     /**
     * 关联产品
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct(){
    	return $this->hasOne(Product::class,['id' => 'pid']);
    }

    /**
     * 关联命令
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirective(){
    	return $this->hasMany(Directive::class,['pid' => 'id'])
            ->viaTable(Product::tableName(),['id' => 'pid']);
    }
}

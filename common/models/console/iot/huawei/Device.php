<?php

namespace common\models\console\iot\huawei;



use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\HuaweiMap;

/**
 * This is the model class for table "rf_lx_iot_huawei_device".
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
        return 'rf_lx_iot_huawei_device';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'number'], 'unique'],
            [['pid', 'number'], 'required'],
            [['pid', 'sort', 'status', 'created_at', 'updated_at', 'switch', 'last_time'], 'integer'],
            [['device_name'], 'string', 'max' => 50],
            [['card'], 'string', 'max' => 30],
            [['number'], 'string', 'max' => 100],
            [['device_id'], 'string', 'max' => 255],
            ['description', 'string', 'max' => 140],
            ['over_time', 'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_name' => '设备名称',
            'switch' => '开关',
            'last_time' => '最后上线时间',
            'over_time' => 'Nb卡过期时间',
            'pid' => '产品',
            'card' => '卡号',
            'number' => '编号',
            'device_id' => '设备ID',
            'description' => '备注',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
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

    /**
     * 设备状态
     *
     * @return html
     */
    public function getDeviceStatus()
    {
        return Value::find()->where(['pid' => $this->id])
            ->andWhere(['between', 'event_time', strtotime('-1 day'), time()])
            ->exists() ? '<span class="label label-info">在线</span>' : '<span class="label label-warning">离线</span>';
    }

    public static function getOnline($id)
    {
        return Value::find()->where(['pid' => $id])
            ->andWhere(['between', 'event_time', strtotime('-1 day'), time()])
            ->exists();
    }

    /**
     * 电量状态
     *
     * @return html
     */
    public function getDeviceVoltage()
    {
        $model = Value::find()->where(['pid' => $this->id])->orderBy('event_time desc')->one();
        return ($model->value['voltage'] < 3.2 && $model->value['voltage']) ? '<span class="label label-warning">电量过低</span>' : '';
    }

    /**
     * 关联产品
     *
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::class, ['id' => 'pid']);
    }

    /**
     * 关联服务
     *
     * @return \yii\db\ActiveQuery
     */
    public function getService()
    {
        return $this->hasMany(Service::class, ['pid' => 'id'])
            ->viaTable(Product::tableName(), ['id' => 'pid']);
    }

    public function getValue()
    {
        return $this->hasOne(Value::class, ['pid' => 'id']);
    }

    /**
     * 关联命令
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirective()
    {
        return $this->hasMany(Directive::class, ['pid' => 'id'])->viaTable(Product::tableName(), ['id' => 'pid']);
    }


    /**
     * 最新数据
     *
     * @return \yii\db\ActiveQuery
     */
    public function getNewValue()
    {
        return $this->hasOne(Value::class, ['pid' => 'id'])
            ->orderBy('event_time desc,id desc');
    }


    /**
     * 关联监测点
     *
     * @return \yii\db\ActiveQuery
     */
    public function getPoint()
    {
        return $this->hasOne(Point::class, ['id' => 'point_id'])
            ->viaTable(HuaweiMap::tableName(), ['device_id' => 'id']);
    }
}

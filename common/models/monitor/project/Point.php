<?php

namespace common\models\monitor\project;

use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use Yii;
use common\models\monitor\project\log\WarnLog;
use common\helpers\ArrayHelper;
use common\models\console\iot\ali\Device as AliDevice;
use common\models\console\iot\huawei\Device;
use common\models\console\iot\huawei\Value as HuaweiValue;
use common\models\monitor\project\point\AliMap;
use common\models\monitor\project\point\HuaweiMap;
use common\models\monitor\project\point\Value;
use common\models\monitor\project\point\Warn;

/**
 * This is the model class for table "rf_lx_monitor_point".
 *
 * @property int $id
 * @property int $pid 房屋ID
 * @property string $title 名称
 * @property int $type 监测类型
 * @property string $location 位置
 * @property array $covers 照片
 * @property array $warn 预警值
 * @property double $lng 经度
 * @property double $lat 纬度
 * @property string $initial_value 初始数据
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Point extends \common\models\base\BaseModel
{

    public $lnglat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'title'], 'required'],
            [['pid', 'type', 'warn_switch', 'warn_type', 'sort', 'news', 'warn', 'status', 'created_at', 'updated_at'], 'integer'],
            [['covers', 'lnglat'], 'safe'],
            [['lng', 'lat', 'initial_value'], 'number'],
            [['title'], 'string', 'max' => 50],
            [['location'], 'string', 'max' => 100],
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
            'pid' => '房屋',
            'title' => '标题',
            'type' => '类型',
            'warn_type' => '倾斜危险类型',
            'location' => '位置',
            'covers' => '图像',
            'news' => '朝向',
            'warn' => '报警',
            'warn_switch' => '报警开关',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'initial_value' => '初始数据',
            'description' => '备注',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }



    /**
     * 预警等级改变时添加日志
     * 
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($changedAttributes['warn'] != $this->warn) {
            // 添加日志
            $logData = [
                'pid' => $this->id,
                'warn' => $this->warn,
            ];
            $this->addLog($logData);
        }

        return parent::afterSave($insert, $changedAttributes);
    }

    /**
     * 返回最新数据
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function getNewValue()
    {
        return $this->hasOne(Value::class, ['pid' => 'id'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('event_time desc');
    }

    /**
     * 返回类型
     * 
     * @param {*} $id
     * @return number 
     * @throws: 
     */
    public static function getType($id)
    {
        $model = self::findOne($id);
        return $model['type'];
    }

    /**
     * 房屋关联
     * 
     * @param 
     * @return array
     * @throws: 
     */
    public function getHouse()
    {
        return $this->hasOne(House::class, ['id' => 'pid']);
    }

    /**
     * 根据房屋id返回监测点ID
     * 
     * @param {*} $pid
     * @return array
     * @throws: 
     */
    public static function getColumn($pid)
    {
        $model = self::find()
            ->where(['pid' => $pid])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        return ArrayHelper::getColumn($model, 'id', $keepKeys = true);
    }

    /**
     * 根据房屋id返回监测点ID
     * 
     * @param {*} $pid
     * @return array
     * @throws: 
     */
    public static function getMap($pid)
    {
        $model = self::find()
            ->where(['pid' => $pid])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        return ArrayHelper::map($model, 'id', 'title');
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function getDeviceValue()
    {
        return $this->hasOne(HuaweiValue::class, ['pid' => 'device_id'])
            ->andWhere(['serviceType' => 'Heartbeat'])
            ->orderBy('event_time DESC')
            ->viaTable(HuaweiMap::tableName(), ['point_id' => 'id']);
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 关联报警
     */
    public function getWarnState()
    {
        return $this->hasOne(Warn::class, ['pid' => 'id'])
            ->andWhere(['>', 'warn', WarnEnum::SUCCESS])
            ->andWhere(['state' => WarnStateEnum::AUDIT])
            ->orderBy('id DESC');
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 关联华为
     */
    public function getDevice()
    {
        return $this->hasOne(Device::class, ['id' => 'device_id'])
            ->viaTable(HuaweiMap::tableName(), ['point_id' => 'id']);
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 关联华为
     */
    public function getAliDevice()
    {
        return $this->hasOne(AliDevice::class, ['id' => 'device_id'])
            ->viaTable(AliMap::tableName(), ['point_id' => 'id']);
    }



    /**
     * 关联命令
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDeviceMap()
    {
        return $this->hasOne(HuaweiMap::class, ['point_id' => 'id']);
    }



    /**
     * 添加日志
     * 
     * @param arrray $data
     * @return 
     * @throws: 
     */
    public function addLog($data = [])
    {
        $log = new WarnLog();
        $log = $log->loadDefaultValues();
        $log->attributes = $data;
        $log->save();

        return $log;
    }
}

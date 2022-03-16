<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:14:00
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-10 15:09:42
 * @Description: 
 */

namespace common\models\monitor\project\point;

use common\enums\StatusEnum;
use Yii;
use common\models\console\iot\huawei\Device;
use common\models\monitor\project\Point;
use common\helpers\ArrayHelper;
use common\models\monitor\project\House;

/**
 * This is the model class for table "rf_lx_monitor_point_device_huawei_map".
 *
 * @property int $id
 * @property int $device_id 设备id
 * @property int $point_id 监测点id
 * @property int $install_time 安装时间
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class HuaweiMap extends \common\models\base\BaseModel
{
    public $lnglat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_device_huawei_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['device_id', 'point_id'], 'required'],
            [['height', 'lng', 'lat'], 'number'],
            [['device_id', 'axis', 'point_id', 'is_up', 'status', 'created_at', 'updated_at'], 'integer'],
            [['covers', 'install_time', 'lnglat'], 'safe'],
            [['location'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'device_id' => '设备',
            'point_id' => '监测点位',
            'is_up' => '朝向',
            'axis' => '坐标',
            'covers' => '图像',
            'lng' => '经度',
            'lat' => 'L纬度',
            'height' => '安装高度',
            'install_time' => '安装时间',
            'location' => '安装位置',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if (!empty($this->lnglat)) {

            // $lnglat =Map::bd_decrypt($this->lnglat['lng'],$this->lnglat['lat']);
            $this->lng = $this->lnglat['lng'];
            $this->lat = $this->lnglat['lat'];
        }

        return parent::beforeSave($insert);
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function getDevice()
    {
        return $this->hasOne(Device::class, ['id' => 'device_id']);
    }

    public function getPoint()
    {
        return $this->hasOne(Point::class, ['id' => 'point_id']);
    }

    public function getHouse()
    {
        return $this->hasOne(House::class, ['id' => 'pid'])
            ->viaTable(Point::tableName(), ['id' => 'point_id']);
    }

    public function getValue()
    {
        return $this->hasOne(Value::class, ['pid' => 'id'])
            ->viaTable(Point::tableName(), ['id' => 'point_id'])
            ->orderBy('event_time desc')
            ->andWhere(['status' => StatusEnum::ENABLED]);
    }


    public static function getPointColumn($device_id)
    {
        $model = self::find()
            ->where(['device_id' => $device_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model, 'point_id', $keepKeys = true);
    }


    public static function getPointCount($device_id)
    {
        return self::find()
            ->where(['device_id' => $device_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->groupBy('point_id')
            ->count();
    }
}

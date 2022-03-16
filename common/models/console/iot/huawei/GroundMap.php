<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-02-23 11:27:27
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-23 12:06:16
 * @Description: 
 */

namespace common\models\console\iot\huawei;

use Yii;
use common\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rf_lx_monitor_house_ground_map".
 *
 * @property int $id
 * @property int $ground_id 设备id
 * @property int $device_id 房屋id
 * @property int $sort 排序
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class GroundMap extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_huawei_device_ground_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ground_id', 'device_id'], 'required'],
            [['ground_id', 'device_id', 'sort', 'status'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'ground_id' => 'Ground ID',
            'device_id' => 'House ID',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    public function getDevice()
    {
        return $this->hasOne(Device::class, ['id' => 'device_id']);
    }

    /**
     * @param $ground_id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getHouseMap($ground_id)
    {
        $model = self::find()
            ->where(['ground_id' => $ground_id])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model, 'device_id');
    }

    /**
     * @param $ground_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addHouses($ground_id, $houses)
    {
        // 删除原有标签关联;
        if ($ground_id && !empty($houses)) {
            $data = [];

            foreach ($houses as $v) {
                $data[] = [$v, $ground_id];
            }

            $field = ['device_id', 'ground_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

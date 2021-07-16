<?php

namespace common\models\monitor\project;

use Yii;
use common\helpers\ArrayHelper;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "rf_lx_monitor_house_ground_map".
 *
 * @property int $id
 * @property int $ground_id 设备id
 * @property int $house_id 房屋id
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
        return 'rf_lx_monitor_house_ground_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ground_id', 'house_id'], 'required'],
            [['ground_id', 'house_id', 'sort', 'status'], 'integer'],
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
            'house_id' => 'House ID',
            'sort' => 'Sort',
            'status' => 'Status',
        ];
    }

    public function getHouse()
    {
        return $this->hasOne(House::class,['id' => 'house_id']);
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
        return ArrayHelper::getColumn($model,'house_id');
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

            $field = ['house_id', 'ground_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

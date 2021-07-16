<?php

namespace common\models\member\base;

use Yii;
use common\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use common\models\monitor\project\House;

/**
 * This is the model class for table "rf_member_ground_house_map".
 *
 * @property int $house_id 房屋id
 * @property int $ground_id 分组id
 */
class HouseMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_member_ground_house_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['house_id', 'ground_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'house_id' => 'House ID',
            'ground_id' => 'Ground ID',
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

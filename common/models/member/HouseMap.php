<?php

namespace common\models\member;

use common\enums\StatusEnum;
use Yii;
use common\helpers\ArrayHelper;
use yii\db\ActiveRecord;
use common\models\monitor\project\House;
use common\models\monitor\project\Point;
/**
 * This is the model class for table "rf_member_house_map".
 *
 * @property int $house_id 房屋id
 * @property int $member_id 用户id
 */
class HouseMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_member_house_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['house_id', 'member_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'house_id' => 'House ID',
            'member_id' => 'Member ID',
        ];
    }

    public function getHouse()
    {
        return $this->hasOne(House::class,['id' => 'house_id']);
    }

    /**
     * @param $member_id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getHouseMap($member_id)
    {   
        $model = self::find()
            ->where(['member_id' => $member_id])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'house_id');
    }


    public static function getPointColumn($member_id,$pid = null)
    {
        $house_id = self::getHouseMap($member_id);
        $model = Point::find()
            ->where(['in','pid',$house_id])
            ->andFilterWhere(['pid' => $pid])
            ->andWhere(['status' =>StatusEnum::ENABLED])
            ->asArray()
            ->all();
            return ArrayHelper::getColumn($model,'id');
        
        
    }

    /**
     * @param $ground_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addHouses($member_id, $houses)
    {
        // 删除原有标签关联;
        if ($member_id && !empty($houses)) {
            $data = [];

            foreach ($houses as $v) {
                $data[] = [$v, $member_id];
            }

            $field = ['house_id', 'member_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

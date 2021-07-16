<?php

namespace common\models\member\base;

use Yii;
use common\helpers\ArrayHelper;
use common\models\member\Member;

/**
 * This is the model class for table "rf_member_ground_map".
 *
 * @property int $member_id 用户id
 * @property int $ground_id 分组id
 */
class GroundMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_member_ground_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'ground_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'member_id' => 'Member ID',
            'ground_id' => 'Ground ID',
        ];
    }

    public function getMember()
    {
        return $this->hasOne(Member::class,['id' => 'member_id']);
    }

    /**
     * @param $ground_id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getMemberMap($ground_id)
    {   
        $model = self::find()
            ->where(['ground_id' => $ground_id])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'member_id');
    }

    public static function getHouseMap($member)
    {
        $model = self::find()
            ->where(['member_id' => $member])
            ->asArray()
            ->all();
        $ids = [];
        foreach ($model as $key => $value) {
            $houseIds = HouseMap::getHouseMap($value['ground_id']);
            $ids = array_merge($ids,$houseIds);

        }
        return $ids;


    }

    /**
     * @param $ground_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addMembers($ground_id, $members)
    {
        // 删除原有标签关联;
        if ($ground_id && !empty($members)) {
            $data = [];

            foreach ($members as $v) {
                $data[] = [$v, $ground_id];
            }

            $field = ['member_id', 'ground_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

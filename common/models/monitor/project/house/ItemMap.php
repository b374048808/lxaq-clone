<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-16 10:53:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-16 15:55:01
 * @Description: 
 */

namespace common\models\monitor\project\house;

use Yii;
use common\models\monitor\project\House;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_monitor_item_house_map".
 *
 * @property int $item_id 项目id
 * @property int $house_id 建筑物id
 */
class ItemMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item_house_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['item_id', 'house_id'], 'required'],
            [['item_id', 'house_id'], 'integer'],
            [['item_id', 'house_id'], 'unique', 'targetAttribute' => ['item_id', 'house_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'house_id' => 'House ID',
        ];
    }


    public static function getHouseIds($id)
    {
        $model = self::find()
            ->where(['item_id' => $id])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'house_id', $keepKeys = true);
    }


    public function getHouse()
    {
        return $this->hasOne(House::class,['id' => 'house_id']);
    }

    /**
     * @param $item_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addHouses($item_id, $houses)
    {
        // 删除原有标签关联;
        if ($item_id && !empty($houses)) {
            $data = [];

            foreach ($houses as $v) {
                $data[] = [$v, $item_id];
            }

            $field = ['house_id', 'item_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

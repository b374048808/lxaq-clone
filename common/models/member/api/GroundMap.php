<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-31 10:54:19
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-31 14:01:39
 * @Description: 客户分组关联
 */

namespace common\models\member\api;

use Yii;

/**
 * This is the model class for table "rf_member_api_ground_map".
 *
 * @property int $ground_id 分组id
 * @property int $house_id 建筑物id
 */
class GroundMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_member_api_ground_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['ground_id', 'house_id'], 'required'],
            [['ground_id', 'house_id'], 'integer'],
            [['ground_id', 'house_id'], 'unique', 'targetAttribute' => ['ground_id', 'house_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'ground_id' => 'Ground ID',
            'house_id' => 'House ID',
        ];
    }


    /**
     * @param $site_id
     * @param $tags
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addManager($ground_id, $house_id)
    {
        // 删除原有标签关联
        if ($ground_id && !empty($house_id)) {
            $field = ['ground_id', 'house_id'];
            $data = [];
            foreach ($house_id as $key => $value) {
                if(self::find()->where(['house_id' => $value,'ground_id' => $ground_id])->exists())
                    continue;
                $data[] = [$ground_id, $value];
            }
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(),$field,$data)->execute();
            return true;
        }
        return false;
    }
}

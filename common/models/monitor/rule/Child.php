<?php

namespace common\models\monitor\rule;

use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\monitor\project\House;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_rule_child".
 *
 * @property int $id
 * @property int $rule_id 规则_id
 * @property int $house_id 房屋_id
 * @property int $status 状态
 */
class Child extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_rule_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['rule_id', 'house_id'], 'required'],
            [['rule_id', 'house_id', 'status', 'created_at', 'updated_at'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'rule_id' => 'Rule ID',
            'house_id' => 'House ID',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public static function getChild($house_id, $type = '')
    {
        global $itemType;
        $itemType = $type;
        $model = self::find()
            ->with(['item' => function($queue){
                global $itemType;
                $queue->andFilterWhere(['type' => $itemType])
                    ->groupBy(['type','warn'])
                    ->orderBy('value asc');
            }])
            ->where(['house_id' => $house_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        
        return  $model;
    }


    public function getHouse(){
        return $this->hasOne(House::class,['id' => 'house_id']);
    }

    public function getItem(){
        return $this->hasMany(RuleItem::class,['pid' => 'rule_id']);
    }

    public function getPoint()
    {
        return $this->hasMany(Point::class,['pid' => 'id'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->viaTable(House::class,['id' => 'house_id']);
    }

     /**
     * @param $rule_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addHouses($rule_id, $houses)
    {
        // 删除原有标签关联;
        if ($rule_id && !empty($houses)) {
            $data = [];

            foreach ($houses as $v) {
                $data[] = [$v, $rule_id];
            }

            $field = ['house_id', 'rule_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
    

    /**
     * @param $rule_id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getHouseMap($rule_id)
    {   
        $model = self::find()
            ->where(['rule_id' => $rule_id])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'house_id');
    }
}

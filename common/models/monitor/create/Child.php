<?php

namespace common\models\monitor\create;

use common\enums\PointEnum;
use common\models\monitor\project\Point;
use common\helpers\ArrayHelper;
use common\models\monitor\project\House;
use Yii;
use common\enums\StatusEnum;

/**
 * This is the model class for table "rf_lx_monitor_create_simple_child".
 *
 * @property int $id
 * @property int $simple_id 规则_id
 * @property int $point_id 房屋_id
 */
class Child extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_create_simple_child';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['simple_id', 'point_id'], 'required'],
            [['simple_id', 'point_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'simple_id' => 'Simple ID',
            'point_id' => 'Point ID',
        ];
    }


    public function getPoint()
    {
        return $this->hasOne(Point::class,['id' => 'point_id']);
    }

    // 监测点所在项目
    public function getHouse()
    {
        return  $this->hasOne(House::class, ['id' => 'pid'])->viaTable(Point::tableName(), ['id' => 'point_id']);
    }


    /**
     * @param $simple_id
     * @return array
     * @throws \yii\db\Exception
     */
    public static function getHouseMap($simple_id)
    {   
        $model = self::find()
            ->where(['simple_id' => $simple_id])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'point_id');
    }

    /**
     * @param $simple_id
     * @param $points
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addPoints($simple_id, $points)
    {
        // 删除原有标签关联;
        if ($simple_id && !empty($points)) {
            $data = [];

            foreach ($points as $v) {
                $data[] = [$v, $simple_id];
            }

            $field = ['point_id', 'simple_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }

}

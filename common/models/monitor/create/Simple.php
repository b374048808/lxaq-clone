<?php

namespace common\models\monitor\create;

use common\enums\StatusEnum;
use common\models\monitor\create\Child;
use common\models\monitor\project\Point;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_create_simple".
 *
 * @property int $id
 * @property string $title 名称
 * @property int $start_time 起始时间
 * @property int $end_time 结束时间
 * @property int $interval 间隔时间
 * @property string $start_value 开始范围
 * @property string $end_value 结束范围
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Simple extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_create_simple';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title','start_time', 'end_time', 'interval','start_value', 'end_value'], 'required'],
            [['interval', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['start_value', 'end_value'], 'number'],
            [['title'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 140],
            [['start_time', 'end_time'],'safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '名称',
            'start_time' => '起始时间',
            'end_time' => '结束时间',
            'interval' => '间隔',
            'start_value' => '起始值',
            'end_value' => '最大值',
            'description' => '描述',
            'sort' => '排序',
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
        $this->start_time = strtotime($this->start_time);
        $this->end_time = strtotime($this->end_time);

        return parent::beforeSave($insert);
    }

    public function getChild()
    {
        return $this->hasMany(Child::class,['simple_id' => 'id']);
    }

    public function getPoint()
    {
        return $this->hasMany(Point::class,['id' => 'point_id'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->viaTable(Child::tableName(),['simple_id' => 'id']);
    }
}

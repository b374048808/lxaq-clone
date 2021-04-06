<?php

namespace common\models\monitor\project;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_monitor_item".
 *
 * @property int $id
 * @property string $title 项目照片
 * @property string $cover 项目照片
 * @property string $hold 负责人
 * @property string $mobile 联系方式
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Item extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['cover'], 'string', 'max' => 100],
            [['hold'], 'string', 'max' => 10],
            [['mobile'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 140],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Title',
            'cover' => 'Cover',
            'hold' => 'Hold',
            'mobile' => 'Mobile',
            'description' => 'Description',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDropDown()
    {
        $models = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->orderBy('sort asc,created_at asc')
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($models);
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }
}

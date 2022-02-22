<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-24 14:10:30
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 16:13:28
 * @Description: 
 */

namespace common\models\company\service;

use Yii;
use common\models\worker\Worker;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * This is the model class for table "rf_lx_service_template".
 *
 * @property int $id
 * @property string $title 标题
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Template extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_service_template';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title'], 'unique'],
            [['title'], 'required'],
            [['sort','push_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 50],
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
            'description' => '描述',
            'push_id' => '负责人',
            'sort' => 'Sort',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

     /**
     * 关联产品
     *
     * @return \yii\db\ActiveQuery
     */
    public function getSteps(){
    	return $this->hasMany(Steps::class,['pid' => 'id']);
    }

    /**
     * 关联管理账号
     *
     * @return \yii\db\ActiveQuery
     */
    public function getWorker()
    {
        return $this->hasOne(Worker::class,['id' => 'push_id']);
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

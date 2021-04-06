<?php

namespace addons\RfReport\common\models;

use Yii;

/**
 * This is the model class for table "rf_addon_report_model".
 *
 * @property int $id
 * @property int $cate_id 模版类型
 * @property string $title 标题
 * @property string $file 模版地址
 * @property int $status 状态[-1:删除;0:已使用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Model extends \common\models\base\BaseModel
{

    public $chars = [];
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_addon_report_model';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['cate_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'file'], 'required'],
            [['title'], 'string', 'max' => 50],
            [['file'], 'string', 'max' => 100],
            ['chars','safe']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'cate_id' => '分类ID',
            'title' => '模版名称',
            'chars' => '标签',
            'file' => '模版地址',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }
    /*  */
    public function getChars()
    {
        return $this->hasMany(Char::class, ['id' => 'char_id'])
            ->viaTable(CharMap::tableName(), ['model_id' => 'id'])
            ->asArray();
    }

    /* 
    *  关联分类
    *
        @return  \yii\db\ActiveQuery 
    */

    public function getCate()
    {
        return $this->hasOne(Cate::class,['id' => 'cate_id']);
    }
}

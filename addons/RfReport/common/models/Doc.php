<?php

namespace addons\RfReport\common\models;

use Yii;

/**
 * This is the model class for table "rf_addon_report_doc".
 *
 * @property int $id
 * @property int $pid 模版id
 * @property string $title 标题
 * @property string $file 生成地址
 * @property int $status 状态[-1:删除;0:已使用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Doc extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_addon_report_doc';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'file'], 'required'],
            [['title'], 'string', 'max' => 50],
            [['file'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'title' => 'Title',
            'file' => 'File',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }

    public function getPid()
    {
        return $this->hasOne(Model::class,['id' => 'pid']);
    }
}

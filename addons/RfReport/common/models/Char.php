<?php

namespace addons\RfReport\common\models;

use Yii;
use common\enums\StatusEnum;
/**
 * This is the model class for table "rf_addon_report_model_char".
 *
 * @property int $id
 * @property int $pid 模版id
 * @property string $title 标题
 * @property int $type 类型[1:文字;2:图片;]
 * @property string $char 字符
 * @property string $default 默认
 * @property string $description 描述
 * @property int $status 状态[-1:删除;0:已使用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Char extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_addon_report_char';
    }
    const CHAR = 1;
    const IMG = 2;

    public static $typeMap = [
        self::CHAR => '字符',
        self::IMG => '图片'
    ];

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['type','sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title', 'char'], 'required'],
            [['title', 'char'], 'string', 'max' => 50],
            [['default'], 'string', 'max' => 150],
            [['description'], 'string', 'max' => 140]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => '标题',
            'type' => '类型',
            'char' => '替换字符',
            'default' => '默认值',
            'description' => '说明',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 关联中间表
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTagMap()
    {
        return $this->hasOne(CharMap::class, ['char_id' => 'id']);
    }

    /**
     * @return array
     */
    public static function getCheckTags()
    {
        // 文章标签
        $articleTags = Char::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->select(['id', 'title'])
            ->asArray()
            ->all();

        $tags = [];
        foreach ($articleTags as $tag) {
            $tags[$tag['id']] = $tag['title'];
        }

        return $tags;
    }
}

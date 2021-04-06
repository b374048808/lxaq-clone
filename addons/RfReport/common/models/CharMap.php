<?php

namespace addons\RfReport\common\models;

use Yii;

/**
 * This is the model class for table "rf_addon_report_char_map".
 *
 * @property int $char_id 标签id
 * @property int $model_id 模版id
 */
class CharMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_addon_report_char_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['char_id', 'model_id'], 'integer'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'char_id' => '字符',
            'model_id' => '模版',
        ];
    }

    /**
     * @param $model_id
     * @return CharMap[]
     */
    public static function getCharsByModelId($model_id)
    {
        return self::findAll(['model_id' => $model_id]);
    }

    public function getChar(){
        return $this->hasOne(Char::class,['id' => 'char_id']);
    }

    /**
     * @param $model_id
     * @param $chars
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addChars($model_id, $chars)
    {
        // 删除原有标签关联
        self::deleteAll(['model_id' => $model_id]);
        if ($model_id && !empty($chars)) {
            $data = [];

            foreach ($chars as $v) {
                $data[] = [$v, $model_id];
            }

            $field = ['char_id', 'model_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

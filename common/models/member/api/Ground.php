<?php

namespace common\models\member\api;

use Yii;
use common\helpers\ArrayHelper;
use common\enums\StatusEnum;

/**
 * This is the model class for table "rf_member_api_ground".
 *
 * @property int $id 主键
 * @property int $member_id 用户id
 * @property string $title 标题
 * @property int $sort 排序
 * @property int $level 级别
 * @property int $pid 上级id
 * @property string $tree 树
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Ground extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_member_api_ground';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['tree'], 'string', 'max' => 500],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => 'Member ID',
            'title' => 'Title',
            'sort' => 'Sort',
            'level' => 'Level',
            'pid' => 'Pid',
            'tree' => 'Tree',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }


    /**
     * 获取树状数据
     *
     * @return mixed
     */
    public static function getTree()
    {
        $cates = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['member_id' => Yii::$app->services->merchant->getId()])
            ->asArray()
            ->all();
        return ArrayHelper::itemsMerge($cates);
    }


    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public static function getEditDropDownList($id = '')
    {
        $list = self::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['member_id' => Yii::$app->user->identity->member_id])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->orderBy('sort asc')
            ->asArray()
            ->all();
        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
        return ArrayHelper::merge([0 => '顶级分类'], $data);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public static function getDropDown()
    {
        $models = Ground::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['member_id' => Yii::$app->services->merchant->getId()])
            ->orderBy('sort asc,created_at asc')
            ->asArray()
            ->all();
        return ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');
    }
    
    /**
     * 父
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    /**
     * 子
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getChild()
    {
        return $this->hasMany(self::class, ['pid' => 'id']);
    }

    /**
     * 同
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getWith()
    {
        return $this->hasMany(self::class, ['pid' => 'pid']);
    }

    /**
     * 关联建筑
     * 
     * @return \yii\db\ActiveQuery
     */
    public function getMap()
    {
        return $this->hasMany(GroundMap::class,['ground_id' => 'id']);
    }
}

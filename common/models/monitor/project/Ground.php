<?php

namespace common\models\monitor\project;

use Yii;
use common\helpers\ArrayHelper;
use common\helpers\TreeHelper;

/**
 * This is the model class for table "rf_lx_monitor_house_ground".
 *
 * @property int $id 主键
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
        return 'rf_lx_monitor_house_ground';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'level', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
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
            'title' => '标题',
            'sort' => '排序',
            'level' => '等级',
            'pid' => '父类',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(self::class, ['id' => 'pid']);
    }

    /**
     * @param Ground $parent
     */
    public function setParent(Ground $parent)
    {
        $this->parent = $parent;
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            if ($this->pid == 0) {
                $this->tree = TreeHelper::defaultTreeKey();
            } else {
                $parent = $this->parent;
                $this->level = $parent->level + 1;
                $this->tree = $parent->tree . TreeHelper::prefixTreeKey($parent->id);
            }
        } else {
            // 修改父类
            if ($this->oldAttributes['pid'] != $this->pid) {
                $parent = $this->parent;
                if ($this->pid == 0) {
                    $parent = new self();
                    $parent = $parent->loadDefaultValues();
                }

                $level = $parent->level + 1;
                $tree = $parent->tree . TreeHelper::prefixTreeKey($parent->id ?? 0);
                // 查找所有子级
                $list = Yii::$app->services->houseGround->findChildByID($this->tree, $this->id);

                $distanceLevel = $level - $this->level;
                // 递归修改
                $data = ArrayHelper::itemsMerge($list, $this->id);
                $this->recursionUpdate($data, $distanceLevel, $tree);

                $this->level = $level;
                $this->tree = $tree;
            }
        }

        return parent::beforeSave($insert);
    }

    /**
     * 递归更新数据
     *
     * @param $data
     * @param $distanceLevel
     * @param $tree
     */
    protected function recursionUpdate($data, $distanceLevel, $tree)
    {
        $updateIds = [];
        $itemLevel = '';
        $itemTree = '';

        foreach ($data as $item) {
            $updateIds[] = $item['id'];
            empty($itemLevel) && $itemLevel = $item['level'] + $distanceLevel;
            empty($itemTree) && $itemTree = str_replace($this->tree, $tree, $item['tree']);
            !empty($item['-']) && $this->recursionUpdate($item['-'],$distanceLevel, $tree);

            unset($item);
        }

        !empty($updateIds) && self::updateAll(['level' => $itemLevel, 'tree' => $itemTree],
            ['in', 'id', $updateIds]);
    }


    
}

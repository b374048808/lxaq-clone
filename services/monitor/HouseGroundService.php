<?php

namespace services\monitor;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\monitor\project\Ground;
use common\helpers\ArrayHelper;
use common\helpers\TreeHelper;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class HouseGroundService extends Service
{
    /**
     * 获取下拉
     *
     * @param string $id
     * @return array
     */
    public function getDropDown($id = '')
    {
        $list = Ground::find()
            ->where(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['<>', 'id', $id])
            ->select(['id', 'title', 'pid', 'level'])
            ->asArray()
            ->all();

        $models = ArrayHelper::itemsMerge($list);
        $data = ArrayHelper::map(ArrayHelper::itemsMergeDropDown($models), 'id', 'title');

        return ArrayHelper::merge([0 => '顶级菜单'], $data);
    }

     /**
     * @param $tree
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findChildByID($tree, $id)
    {
        return Ground::find()
            ->where(['like', 'tree', $tree . TreeHelper::prefixTreeKey($id) . '%', false])
            ->select(['id', 'level', 'tree', 'pid'])
            ->asArray()
            ->all();
    }

}

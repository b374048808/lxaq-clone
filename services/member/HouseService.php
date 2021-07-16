<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-24 15:59:18
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-24 16:07:36
 * @Description: 
 */

namespace services\member;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\helpers\ArrayHelper;
use common\models\member\base\GroundMap;
use common\models\member\HouseMap;
use common\models\monitor\project\Point;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class HouseService extends Service
{
    /**
     * 获取用户关联房屋
     *
     * @param string $id
     * @return array
     */
    public function getHouseId($id)
    {
        // 获取自身绑定的房屋
        $ids = HouseMap::getHouseMap($id);
        // 获取账号所在分组关联的房屋
        $groundId = GroundMap::getHouseMap($id);
        // 合并数组
        return array_merge($ids,$groundId);
    }

     /**
     * 获取用户关联房屋的所有监测点
     *
     * @param string $id
     * @return array
     */
    public function getPointId($member)
    {
        $houseId = $this->getHouseId($member);

        $model = Point::find()
            ->where(['in','pid',$houseId])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'id', $keepKeys = true);
    }


}

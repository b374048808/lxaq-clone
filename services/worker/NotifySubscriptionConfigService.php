<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 08:53:13
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 08:53:32
 * @Description: 
 */

namespace services\worker;

use common\components\Service;
use common\enums\SubscriptionAlertTypeEnum;
use common\helpers\ArrayHelper;
use common\models\worker\NotifySubscriptionConfig;

/**
 * Class NotifySubscriptionConfigService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class NotifySubscriptionConfigService extends Service
{
    /**
     * @param $newData
     */
    public function getData($newData)
    {
        $data = SubscriptionAlertTypeEnum::default();

        foreach ($newData as $key => $datum) {
            !empty($datum) && $data[$key] = ArrayHelper::merge($data[$key], $datum);
        }

        return $data;
    }

    /**
     * @param $member_id
     * @return NotifySubscriptionConfig|null
     */
    public function findByMemberId($member_id)
    {
        return NotifySubscriptionConfig::findOne(['member_id' => $member_id]);
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAllWithMember()
    {
        return NotifySubscriptionConfig::find()->with('member')->all();
    }
}
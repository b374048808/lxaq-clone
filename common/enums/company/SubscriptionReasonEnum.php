<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 14:46:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-19 10:52:13
 * @Description: 
 */

namespace common\enums\company;

/**
 * SubscriptionReasonEnum
 *
 * Class SubscriptionReasonEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SubscriptionReasonEnum
{
    // 提醒关联的目标类型组别

    /**
     * 行为创建
     */
    const BEHAVIOR_VERIFY = 'behavior_verify';

    // 订阅原因对应订阅事件
    public static $reasonAction = [
        self::BEHAVIOR_VERIFY => [SubscriptionActionEnum::VERIFY_SUCCESS, SubscriptionActionEnum::VERIFY_OUT],
    ];
}
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-19 10:50:35
 * @Description: 
 */

namespace common\enums\company;

/**
 * Class SubscriptionActionEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SubscriptionActionEnum
{
    /** @var string 行为提醒 隶属行为 */
    const VERIFY_SUCCESS = 'verify_success';
    const VERIFY_OUT = 'verify_out';

    /**
     * @var array
     */
    public static $listExplain = [
        self::VERIFY_SUCCESS => '审批通过',
        self::VERIFY_OUT => '审批驳回',
    ];
}
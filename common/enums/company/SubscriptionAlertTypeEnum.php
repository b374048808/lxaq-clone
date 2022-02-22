<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 14:46:31
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 14:46:53
 * @Description: 
 */

namespace common\enums\company;

/**
 * Class SubscriptionAlertTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SubscriptionAlertTypeEnum
{
    const SYS = 'sys';
    const DINGTALK = 'dingtalk';
    const WECHAT = 'wechat';
    const SMS = 'sms';

    /**
     * @var array
     */
    public static $listExplain = [
        self::SYS => '系统提醒',
        self::DINGTALK => '钉钉提醒(机器人)',
        // self::WECHAT => '微信模板',
        // self::SMS => '短信',
    ];

    /**
     * @return array
     */
    public static function default()
    {
        $data = [];
        $actions = SubscriptionActionEnum::$listExplain;
        foreach (self::$listExplain as $key => $item) {
            $data[$key] = [];

            foreach ($actions as $index => $action) {
                $data[$key][$index] = 0;
            }
        }

        return $data;
    }
}
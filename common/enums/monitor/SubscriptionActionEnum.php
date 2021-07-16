<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 10:42:41
 * @Description: 
 */

namespace common\enums\monitor;

class SubscriptionActionEnum
{
    /** @var string 设备异常 */
    const ALI_OFFLINE = 'ALI_offline';
    const HUAWEI_OFFLINE = 'HUAWEI_offline';

    /** @var string 数据  */
    const VALUE_WARN = 'value_warning';

    /** @var string 时间提醒 */
    const OVER_TIME = 'over_time';

    /**
     * @var array
     */
    public static $listExplain = [
        self::HUAWEI_OFFLINE => '华为设备离线异常',
        self::ALI_OFFLINE => '阿里设备离线异常',
        self::VALUE_WARN => '数据异常',
        self::OVER_TIME => '过期时间提醒',
    ];


    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::HUAWEI_OFFLINE => '华为设备离线异常',
            self::ALI_OFFLINE => '阿里设备离线异常',
            self::VALUE_WARN => '数据异常',
            self::OVER_TIME => '过期时间提醒',
        ];
    }
}
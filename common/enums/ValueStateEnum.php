<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 09:43:19
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-04-30 13:27:00
 * @Description: 
 */

namespace common\enums;

/**
 * 数据状态
 *
 * Class ValueStateEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ValueStateEnum extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;
    const AUDIT = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => '开启',
            self::DISABLED => '关闭',
            self::AUDIT => '审核中',
        ];
    }

    public static function getStateMap():array
    {
        return [
            self::ENABLED => '审核通过',
            self::DISABLED => '驳回',
        ];
    }
}
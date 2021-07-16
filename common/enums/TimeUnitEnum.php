<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-08 15:06:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-08 15:13:45
 * @Description: 
 */

namespace common\enums;

/**
 * 时间类型
 *
 * Class TransferTypeEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class TimeUnitEnum extends BaseEnum
{
    const DAY = 1;
    const HOURS = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DAY => '天',
            self::HOURS => '小时',
        ];
    }

    /**
     * @return array
     */
    public static function getUnitMap(): array
    {
        return [
            self::DAY => 3600*24,
            self::HOURS => 3600,
        ];
    }


    public static function getUnit($id)
    {
        return static::getUnitMap()[$id]?:'';
    }
}
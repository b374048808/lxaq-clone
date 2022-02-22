<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-28 16:08:52
 * @Description: 
 */

namespace common\enums\monitor;

use common\enums\BaseEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class TimeUnitsEnum extends BaseEnum
{

    const MONTH = 1;
    const SEASON = 2;
    const YEAR = 3;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MONTH => '月',
            self::SEASON => '季',
            self::YEAR => '年',
        ];
    }
}
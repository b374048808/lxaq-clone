<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-05 10:52:00
 * @Description: 
 */

namespace common\enums\monitor;

use common\enums\BaseEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ReportEnum extends BaseEnum
{

    const MONITOR = 1;
    const DETECT = 2;
    const OTHER = 99;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MONITOR => '监测报告',
            self::DETECT => '鉴定报告',
            self::OTHER => '其他报告',
        ];
    }
}
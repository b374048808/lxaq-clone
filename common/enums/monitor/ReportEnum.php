<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-02 11:07:44
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

    const MONITOR_REPORT = 1;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MONITOR_REPORT => '监测报告',
        ];
    }
}
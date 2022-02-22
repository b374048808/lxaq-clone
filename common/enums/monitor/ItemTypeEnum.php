<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-26 14:02:29
 * @Description: 
 */

namespace common\enums\monitor;

use common\enums\BaseEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ItemTypeEnum extends BaseEnum
{

    const DETECT = 1;
    const MONITOR = 2;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DETECT => '鉴定',
            self::MONITOR => '监测',
        ];
    }
}
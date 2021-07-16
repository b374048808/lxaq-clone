<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 09:43:19
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-13 15:53:44
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
class WarnStateEnum extends BaseEnum
{
    const AUDIT = 1;
    const DISABLED = 0;
    const CLIENT = 2;
    const COMPANY = 3;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DISABLED => '关闭',
            self::CLIENT => '客户自行处理',
            self::COMPANY => '公司处理',
            self::AUDIT => '待处理',
        ];
    }
}
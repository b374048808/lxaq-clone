<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-28 18:49:30
 * @Description: 
 */

namespace common\enums;

/**
 * 状态枚举
 *
 * Class StatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class StatusEnum extends BaseEnum
{
    const ENABLED = 1;
    const DISABLED = 0;
    const DELETE = -1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => '启用',
            self::DISABLED => '禁用',
            self::DELETE => '已删除',
        ];
    }
}
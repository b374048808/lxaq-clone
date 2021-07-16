<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-02 10:12:39
 * @Description: 
 */

namespace common\enums\monitor;

use common\enums\BaseEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class BellStateEnum extends BaseEnum
{

    const DISABLED = -1;
    const UNFINISHED = 1;
    const COMPLETE = 2;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::DISABLED => '取消',
            self::UNFINISHED => '未处理',
            self::COMPLETE => '完成',
        ];
    }
}
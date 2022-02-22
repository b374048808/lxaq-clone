<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 15:28:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-27 11:38:23
 * @Description: 
 */

namespace common\enums\device;

use common\enums\BaseEnum;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class SwitchEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            0 => '关闭',
            1 => '开启'
        ];
    }
}

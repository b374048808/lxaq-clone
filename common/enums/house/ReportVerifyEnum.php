<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 15:28:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-27 11:36:52
 * @Description: 
 */

namespace common\enums\house;

use common\enums\BaseEnum;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class TypeEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            0 => '未选择',
            1 => '钢结构',
            2 => '钢、钢筋混凝土结构',
            3 => '钢筋混凝土结构',
            4 => '混合结构',
            5 => '砖木结构',
            6 => '木结构',
            7 => '其他结构',
        ];
    }
}
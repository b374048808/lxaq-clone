<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 15:28:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-27 11:39:09
 * @Description: 
 */

namespace common\enums\house;

use common\enums\BaseEnum;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class RoofEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            1 => '木屋盖',
            2 => '预制多孔板',
            3 => '钢筋混凝土现浇板',
            4 => '小梁小板',
            5 => '石板',
            6 => '平屋顶',
            7 => '坡屋顶'
        ];
    }
}
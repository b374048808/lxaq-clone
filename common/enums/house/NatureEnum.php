<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 15:28:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-27 11:38:23
 * @Description: 
 */

namespace common\enums\house;

use common\enums\BaseEnum;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class NatureEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            1 => '住宅',
            2 => '商业用房',
            3 => '办公用房',
            4 => '教育用房',
            5 => '医院用房',
            6 => '体育用房',
            7 => '其他公共类建筑',
            8 => '工业用房',
            9 => '非住宅营业房',
            10 => '住宅出租房',
            11 => '其他',
        ];
    }
}
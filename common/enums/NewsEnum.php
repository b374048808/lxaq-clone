<?php

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class NewsEnum extends BaseEnum
{
    const NORTH = 1;  
    const NORTEAST = 2;
    const EAST = 3;
    const SOUTHEAST = 4;
    const SOUTH = 5;
    const SOUTHWEST = 6;
    const WEST = 7;
    const NORTHWEST = 8;  

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::NORTH => '北',  
            self::NORTEAST => '东北',
            self::EAST => '东',
            self::SOUTHEAST => '东南',
            self::SOUTH => '南',
            self::SOUTHWEST => '西南',
            self::WEST => '西',
            self::NORTHWEST => '西北',  
        ];
    }
}
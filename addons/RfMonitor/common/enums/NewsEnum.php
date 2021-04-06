<?php

namespace addons\RfMonitor\common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class NewsEnum 
{
    const NORTH = 1;
    const EASTNORTH = 2;
    const EAST = 3;
    const EASTSOUTH = 4;
    const SOUTH = 5;
    const WESTSOUTH = 6;
    const WEST = 7;
    const WESTNORTH = 8;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::NORTH => '北',
            self::EASTNORTH => '东北',
            self::EAST => '东',
            self::EASTSOUTH => '东南',
            self::SOUTH => '南',
            self::WESTSOUTH => '西南',
            self::WEST => '西',
            self::WESTNORTH => '西北',
        ];
    }

}
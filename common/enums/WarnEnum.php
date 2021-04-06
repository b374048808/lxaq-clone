<?php

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WarnEnum extends BaseEnum
{
    const SUCCESS = 1;
    const BLUE = 2;
    const YELLOW = 3;
    const ORANGE = 4;
    const RED = 5;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::Ali => '阿里物联网',
            self::HUAWEI => '华为物联网',
        ];
    }

    /**
     * @return array
     */
    public static function getRgbMap(): array
    {
        return [
            self::SUCCESS => '115, 214, 97',
            self::BLUE => '39, 108, 254',
            self::YELLOW => '255, 230, 0',
            self::ORANGE => '255, 90, 0',
            self::RED => '246, 3, 23',
        ];
    }
}
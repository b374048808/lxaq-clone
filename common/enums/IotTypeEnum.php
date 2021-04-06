<?php

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class IotTypeEnum extends BaseEnum
{
    const Ali = 1;
    const HUAWEI = 2;

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
}
<?php

namespace common\enums;

/**
 * Class WhetherEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ValueTypeEnum extends BaseEnum
{
    const MANUAL = 2;
    const AUTOMATIC = 1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::AUTOMATIC => '设备数据',
            self::MANUAL => '人工数据',
        ];
    }
}
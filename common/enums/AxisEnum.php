<?php

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AxisEnum extends BaseEnum
{
    const XAXIS = 1;  
    const YAXIS = 2;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::XAXIS => 'X轴',  
            self::YAXIS => 'Y轴',
        ];
    }


    public static function getAxis(): array{
        return [
            self::XAXIS => 'xAxisAngle',  
            self::YAXIS => 'yAxisAngle',
        ];
    }

    /**
     * @param $key
     * @return string
     */
    public static function getAxisValue($key): string
    {
        return static::getAxis()[$key] ?? '';
    }
}
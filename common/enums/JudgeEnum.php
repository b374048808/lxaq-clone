<?php

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class JudgeEnum extends BaseEnum
{
    const BIG = 1;
    const SMALL = 2;
    const EQUAL = 3;
    const BIGEQUAL = 4;
    const SMALLEQUAL = 5;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::BIG => '>',
            self::SMALL => '<',
            self::EQUAL => '=',
            self::BIGEQUAL => '>=',
            self::SMALLEQUAL => '<=',
        ];
    }
}
<?php

namespace common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AttrTypeEnum extends BaseEnum
{
    const INT = 1;
    const DECIMAL = 2;
    const STRING = 3;
    const DATETIME = 4;
    const JSON = 5;
    const ARRAY = 6;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::INT => 'int(整型)',
            self::DECIMAL => 'decimal(小数)',
            self::STRING => 'string(字符串)',
            self::DATETIME => 'dateTime(日期时间)',
            self::JSON => 'jsonObject(JSON结构体)',
            self::ARRAY => 'stringList(数组)',
        ];
    }
}
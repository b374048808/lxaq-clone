<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 11:14:50
 * @Description: 
 */

namespace common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class CardEnum extends BaseEnum
{

    const MONTH = 1;
    const YEAR = 2;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::MONTH => '月卡',
            self::YEAR => '年卡',
        ];
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 套餐
     */    
    public static function getPackageMap(): array
    {
        // 不更改顺序添加
        return [
            '2G/年',
        ];
    }

    public static function getPackageValue($id)
    {
        return self::getPackageMap()[$id];
    }

    /**
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public static function getOperatorMap(): array
    {
        // 不更改顺序添加
        return [
            '电信',
            '移动',
            '联通'
        ];
    }

    public static function getOperatorValue($id)
    {
        return self::getOperatorMap()[$id];
    }
}
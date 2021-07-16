<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 15:28:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-26 14:32:45
 * @Description: 
 */

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class StructEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            
        ];
    }
    /**
     * 房屋类型
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */ 
    public static function natureMap(): array
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

    /**
     * @param $key
     * @return string
     */
    public static function getNatureValue($key): string
    {
        return static::natureMap()[$key] ?? '';
    }


    /**
     * 房屋结构
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public static function typeMap(): array
    {
        return [
            0 => '未选择',
            1 => '钢结构',
            2 => '钢、钢筋混凝土结构',
            3 => '钢筋混凝土结构',
            4 => '混合结构',
            5 => '砖木结构',
            6 => '木结构',
            7 => '其他结构',
        ];
    }

    /**
     * @param $key
     * @return string
     */
    public static function getTypeValue($key): string
    {
        return static::typeMap()[$key] ?? '';
    }


    /**
     * 屋面形式
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public static function roofMap(): array
    {
        return [
            0 => '未选择',
            1 => '木屋盖',
            2 => '预制多孔板',
            3 => '钢筋混凝土现浇板',
            4 => '小梁小板',
            5 => '石板',
            6 => '平屋顶',
            7 => '坡屋顶'
        ];
    }

    /**
     * @param $key
     * @return string
     */
    public static function getRootValue($key): string
    {
        return static::roofMap()[$key] ?? '';
    }

}
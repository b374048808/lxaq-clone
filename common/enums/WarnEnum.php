<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-16 15:46:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-03 16:01:53
 * @Description: 
 */

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WarnEnum extends BaseEnum
{
    const SUCCESS = 0;
    const ONE = 1;
    const TWO = 2;
    const THREE = 3;
    const FOUR = 4;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::SUCCESS => '未报警',
            self::ONE => '一级预警',
            self::TWO => '二级预警',
            self::THREE => '三级预警',
            self::FOUR => '四级预警',
        ];
    }

    /**
     * @var array
     */
    public static $spanlistExplain = [
        self::SUCCESS => '<span class="label label-success">正常</span>',
        self::ONE => '<span class="label label-info">一级预警</span>',
        self::TWO => '<span class="label label-primary">二级预警</span>', 
        self::THREE => '<span class="label label-warning">三级预警</span>',
        self::FOUR => '<span class="label label-danger">四级预警</span>',
    ];


    /**
     * @var array
     */
    public static $tagType = [
        self::SUCCESS => 'success',
        self::ONE => 'primary',
        self::TWO => 'warning', 
        self::THREE => 'danger',
    ];
}
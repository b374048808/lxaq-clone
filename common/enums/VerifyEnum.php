<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 15:28:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-28 16:13:12
 * @Description: 
 */

namespace common\enums;

use common\enums\BaseEnum;
use common\helpers\Html;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class VerifyEnum extends BaseEnum
{
    const PASS = 2;
    const WAIT = 1;
    const SAVE = 0;
    const OUT = -1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::PASS => '通过',
            self::WAIT => '待审核',
            self::SAVE => '未提交',
            self::OUT => '驳回',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::PASS => Html::tag('span', self::getValue(self::PASS), array_merge(
                [
                    'class' => "label label-success",
                ]
            )),
            self::WAIT => Html::tag('span', self::getValue(self::WAIT), array_merge(
                [
                    'class' => "label label-info",
                ]
            )),
            self::SAVE => Html::tag('span', self::getValue(self::SAVE), array_merge(
                [
                    'class' => "label label-default",
                ]
            )),
            self::OUT => Html::tag('span', self::getValue(self::OUT), array_merge(
                [
                    'class' => "label label-warning",
                ]
            )),
        ];

        return $html[$key] ?? '';
    }

    /**
     * @param {*} $key
     * @return {*}
     * @throws: 
     */    
    public static function getAudit($key){
        $html = [
            self::PASS => '通过审核',
            self::WAIT => '提交',
            self::SAVE => '撤回',
            self::OUT => '审核驳回',
        ];
        
        return $html[$key] ?? '';
    }

}
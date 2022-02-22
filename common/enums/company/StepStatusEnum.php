<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 17:00:15
 * @Description: 
 */

namespace common\enums\company;

use common\enums\BaseEnum;

/**
 * 状态枚举
 *
 * Class StatusEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class StepStatusEnum extends BaseEnum
{
    const ENABLED = 2;
    const STAY = 1;
    const REJECT = 0;
    const DELETE = -1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ENABLED => '完成',
            self::STAY => '待完成',
            self::REJECT => '驳回',
            self::DELETE => '已删除',
        ];
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {

        $html[self::STAY] ='<i class="fa fa-arrow-circle-right rf-i"></i>';

        return $html[$key] ?? '';
    }
}
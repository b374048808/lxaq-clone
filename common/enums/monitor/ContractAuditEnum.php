<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-09 09:20:37
 * @Description: 
 */

namespace common\enums\monitor;

use common\enums\BaseEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ContractAuditEnum extends BaseEnum
{

    const OUT = 0;
    const WAIT = 1;
    const PASS = 2;
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::OUT => '驳回',
            self::WAIT => '未处理',
            self::PASS => '通过',
        ];
    }
}
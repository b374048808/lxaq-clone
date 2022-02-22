<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-06 09:10:28
 * @Description: 
 */

namespace common\enums\mini;

/**
 * Class SubscriptionActionEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MessageActionEnum
{
    /** @var string 行为提醒 隶属行为 */
    const VERIFY_SUCCESS = 'verify_success';
    const VERIFY_OUT = 'verify_out';
    const VERIFY_WAIT = 'verify_wait';
    const VERIFY_CREATE = 'verify_create';
    const REMIND = 'remind';
    const NUMBER_REMIND = 'number_remind';

    /** @var string 行为提醒 下一步 */
    const STEPS_CONTART = 'steps_contart';
    const STEPS_SERVICE = 'steps_service';
    const STEPS_MONEY = 'steps_money';
    const STEPS_END = 'steps_end';
    const STEPS_OUT = 'steps_out';



    /**
     * @var array
     */
    public static $listExplain = [
        self::VERIFY_CREATE => '审批提交',
        self::VERIFY_SUCCESS => '审批通过',
        self::VERIFY_WAIT => '审批提交',
        self::VERIFY_OUT => '审批驳回',

        self::STEPS_CONTART => '合同阶段',
        self::STEPS_SERVICE => '任务阶段',
        self::STEPS_MONEY => '收款阶段',
        self::STEPS_END => '完成',
        self::STEPS_OUT => '驳回'
    ];
}

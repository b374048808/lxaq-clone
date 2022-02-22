<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 14:46:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-06 09:13:40
 * @Description: 
 */

namespace common\enums\mini;

/**
 * SubscriptionReasonEnum
 *
 * Class SubscriptionReasonEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class MessageReasonEnum
{
    // 提醒关联的目标类型组别

    /**
     * 行为创建
     */
    const SERVICE_VERIFY = 'service_verify';
    const ITEM_VERIFY  = 'item_verify';
    const ITEM_STEPS = 'item_steps';
    const REPORT_VERIFY = 'report_verify';

    // 订阅原因对应订阅事件
    public static $actionTemplate = [
        self::SERVICE_VERIFY => [
            MessageActionEnum::VERIFY_SUCCESS => 'NnvVMQoH2WhlfHKx1Pu4_la92ILzI1KbScS5Xt5p7W0',     //审核结果
            MessageActionEnum::VERIFY_WAIT => 'pzn-HxLf11d7kfjV44BToApQym0Ta3wWuidCKre3An4',        //有待审核
            MessageActionEnum::VERIFY_CREATE => '_6_JyDHODepKSLJTmaCrLqm8eIRASBY2rcYynlR9JLU',      //新任务
            MessageActionEnum::REMIND => 'NqJGLhI6FkRkCgMGoWbYviBb8ehAZk5OdQyAL4Pzg6g',             //任务开始通知
        ],
        self::ITEM_VERIFY => [
            MessageActionEnum::VERIFY_SUCCESS => 'NnvVMQoH2WhlfHKx1Pu4_la92ILzI1KbScS5Xt5p7W0',     //审核结果
            MessageActionEnum::VERIFY_WAIT => 'h53D-tGb0EkkfSxosZpd4AUBenrQmtnqmtVW-gjw-pg',        //有项目提交
            MessageActionEnum::NUMBER_REMIND => 'gapLZdw7T_t8o24ljQMcGAjPOlLLWShog60_8WGx7uI',        //有项目编号待审批
        ],
        self::ITEM_STEPS => [
            MessageActionEnum::STEPS_CONTART => 'qwleBrOpPKrv2uUZfswqwiw3b_fkmAEad8Ysj5NMxpo',      //合同
            MessageActionEnum::STEPS_SERVICE => 'bvmE4STtg6XdErRTuEPoMQshAk3oCIsxzWVwi4V53yc',      //任务
            MessageActionEnum::STEPS_MONEY => 'kGqlIcT5x6e2AM7vvpY0qb65aY7my2O5YnU5CCS6Auc',         //项目进入收款阶段
            MessageActionEnum::STEPS_END => '1jQ5b4MlMQFvxN-1gbgAS3Px-GGtWTRdep6hKtYSbt4',           //项目完成
            MessageActionEnum::STEPS_OUT => '8_MngMAfnVltiqi9Y8M0DKIgwsyP9Pu8t259qRjnztI',           //进度驳回
        ],
        self::REPORT_VERIFY => [
            MessageActionEnum::VERIFY_SUCCESS => '4Wa1oaBgbCD2J9M8YMrfsU7W3PW0Z8BU5k_isPtEoZA',      //审核
            MessageActionEnum::VERIFY_CREATE => 'ZLARLmu5GF_i9wSruVkYPB3nVrGqRwFTiCizpmfoRxE',      //提交
        ],
    ];
}

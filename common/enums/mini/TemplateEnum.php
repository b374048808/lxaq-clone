<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 14:46:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-26 09:32:35
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
class TemplateEnum
{
    // 提醒关联的目标类型组别

    /**
     * 任务提醒
     */
    const SERVICE_START = 'NqJGLhI6FkRkCgMGoWbYviBb8ehAZk5OdQyAL4Pzg6g';    //开始
    const SERVICE_END = 'pzn-HxLf11d7kfjV44BToApQym0Ta3wWuidCKre3An4';      //完成
    const SERVICE_VERIFY = 'NnvVMQoH2WhlfHKx1Pu4_la92ILzI1KbScS5Xt5p7W0';   //审核反馈


    // 项目模板
    const ITEM_VERIFY = 'NnvVMQoH2WhlfHKx1Pu4_la92ILzI1KbScS5Xt5p7W0'; //审核提醒
    const ITEM_SUBMIT = 'h53D-tGb0EkkfSxosZpd4AUBenrQmtnqmtVW-gjw-pg';//提交
    const ITEM_MONEY = 'kGqlIcT5x6e2AM7vvpY0qb65aY7my2O5YnU5CCS6Auc'; // 收款
    const ITEM_CONTRACT = 'qwleBrOpPKrv2uUZfswqwiw3b_fkmAEad8Ysj5NMxpo'; // 合同
    const ITEM_END = '1jQ5b4MlMQFvxN-1gbgAS3Px-GGtWTRdep6hKtYSbt4'; //完成



}
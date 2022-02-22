<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-26 10:27:43
 * @Description: 
 */
return [
    /** ------ 日志记录 ------ **/
    'user.log' => true,
    'user.log.level' => YII_DEBUG ? ['success', 'info', 'warning', 'error'] : ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.except.code' => [], // 不记录的code

    /** ------ token相关 ------ **/
    // token有效期是否验证 默认不验证
    'user.accessTokenValidity' => false,
    // token有效期 默认 2 小时
    'user.accessTokenExpire' => 2 * 60 * 60,
    // refresh token有效期是否验证 默认开启验证
    'user.refreshTokenValidity' => true,
    // refresh token有效期 默认30天
    'user.refreshTokenExpire' => 30 * 24 * 60 * 60,
    // 签名验证默认关闭验证，如果开启需了解签名生成及验证
    'user.httpSignValidity' => false,
    // 签名授权公钥秘钥
    'user.httpSignAccount' => [
        'doormen' => 'e3de3825cfbf',
    ],
    // 触发格式化返回
    'triggerBeforeSend' => true,
    'noAuthRoute' => [
        '/v1/site/login',
        '/v1/monitor/item/rbac',
        '/v1/monitor/service/rbac',
        '/v1/project/report/rbac',
        '/v1/default/search',
        '/v1/site/signature'
    ],
    'adminAccount' => ['2'],
];

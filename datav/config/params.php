<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:34:28
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-23 16:19:27
 * @Description: 
 */
return [
    /** ------ 日志记录 ------ **/
    'user.log' => false,
    'user.log.level' => ['warning', 'error'], // 级别 ['success', 'info', 'warning', 'error']
    'user.log.except.code' => [], // 不记录的code

    /** ------ 非微信打开的时候是否开启微信模拟数据 ------ **/
    'simulateUser' => [
        'switch' => false,// 微信应用模拟用户检测开关
        'userInfo' => [
            'id' => 'oW6qtS0fitZTWHudEX-7ik',
            'nickname' => '简言',
            'name' => '简言',
            'avatar' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM4eoQGHDIsK05kWV5deHKK99ka7d65eecJZ7CRZGTlicuaoH7YzcbzYXo1pDR6N77bdLTwA6F2mZA1cFw7icJxwwSWbVgqk3l6gU/0',
            'original' => [
                'openid' => 'oW6qtS0fitZTWHudEX-7ik',
                'nickname' => '简言',
                'sex' => 1,
                'language' => 'zh_CN',
                'city' => '杭州',
                'province' => '浙江',
                'country' => '中国',
                'headimgurl' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM4eoQGHDIsK05kWV5deHKK99ka7d65eecJZ7CRZGTlicuaoH7YzcbzYXo1pDR6N77bdLTwA6F2mZA1cFw7icJxwwSWbVgqk3l6gU/0',
                'privilege' => [],
            ],
            'token' => '10_8ZUhjEP6s_nanE37Z7Zh3kFRA7ZhFRAALBtkCV1WE',
            'provider' => 'WeChat',
        ],
    ],
// 触发格式化返回
'triggerBeforeSend' => true,

    /** ------ 当前的微信用户信息 ------ **/
    'wechatMember' => [],
];

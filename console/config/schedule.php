<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:44
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-08 09:20:25
 * @Description: 
 */
/**
 * @var \omnilight\scheduling\Schedule $schedule
 */

$path = Yii::getAlias('@runtime') . '/logs/';

/**
 * 清理过期的微信历史消息记录
 *
 * 每天凌晨执行一次
 */
$filePath = $path . 'msgHistory.log';
$schedule->command('wechat/msg-history/index')->cron('0 0 * * *')->sendOutputTo($filePath);

/**
 * 定时群发微信消息
 *
 * 每分钟执行一次
 */
$filePath = $path . 'sendMessage.log';
$schedule->command('wechat/send-message/index')->cron('* * * * *')->sendOutputTo($filePath);

/**
 * 重启ws
 *
 * 每天凌晨执行一次
 */
$filePath = $path . 'websocket.log';
$schedule->command('websocket/stop')->cron('0 0 * * *')->sendOutputTo($filePath);
$schedule->command('websocket/start')->cron('1 0 * * *')->sendOutputTo($filePath);

/**
 * 提醒服务
 *
 * 每天凌晨执行一次
 */
$filePath = $path . 'bell.log';
$schedule->command('bell/card')->cron('0 0 * * *')->sendOutputTo($filePath);
$schedule->command('bell/ali-device')->cron('0 0 * * *')->sendOutputTo($filePath);
$schedule->command('bell/huawei-device')->cron('0 0 * * *')->sendOutputTo($filePath);
$schedule->command('bell/service-remind')->cron('0 8 * * *')->sendOutputTo($filePath);

/**
 * 自动生成数据
 *
 * 每小时执行一次
 */
$filePath = $path . 'rand.log';
$schedule->command('rand/simple')->cron('* * * * *')->sendOutputTo($filePath);
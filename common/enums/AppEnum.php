<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-25 12:27:05
 * @Description: 
 */

namespace common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AppEnum extends BaseEnum
{
    const BACKEND = 'backend';
    const FRONTEND = 'frontend';
    const API = 'api';
    const HTML5 = 'html5';
    const MERCHANT = 'merchant';
    const MER_API = 'merapi';
    const OAUTH2 = 'oauth2';
    const STORAGE = 'storage';
    const CONSOLE = 'console';
    const WORKER = 'workapi';

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::BACKEND => '总后台',
            self::FRONTEND => '前台',
            self::API => '接口',
            self::HTML5 => '手机',
            self::MERCHANT => '商家',
            self::MER_API => '商家接口',
            self::OAUTH2 => 'oauth2',
            self::STORAGE => '存储',
            self::CONSOLE => '控制台',
            self::WORKER => '员工',
        ];
    }

    /**
     * 接口
     *
     * @return array
     */
    public static function api()
    {
        return [self::API, self::MER_API, self::OAUTH2,self::WORKER];
    }

    /**
     * 管理后台
     *
     * @return array
     */
    public static function admin()
    {
        return [self::BACKEND, self::MERCHANT];
    }
}
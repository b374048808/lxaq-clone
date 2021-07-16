<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-22 09:14:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-12 10:39:48
 * @Description: 
 */

namespace common\enums;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AliValueTypeEnum extends BaseEnum
{
    const PARMAS = 3;
    const Heartbeat = 4;
    const RESULT = 3;
    const EMPTY = 10;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::PARMAS => '查看参数',
            self::Heartbeat => '数值返回',
            self::RESULT => '地址重设成功！',
            self::EMPTY => '清零或恢复出厂设置成功！',
        ];
    }

    public static function onValue($data){
        $typeStr = (int)substr($data,2,2);  //类型
        $num = (int)substr($data,4,2);  //数据位数
        $value1 = substr($data,6,$num); //整数
        $value2 = substr($data,6+$num,$num);    //小数

        switch ($typeStr) {
            // 心跳
            case self::Heartbeat:
                $int = hexdec('0'.substr($value1, 1,3));
                $float = hexdec($value2);
                // 数据整合
                return (substr($value1, 0,1) == 8)?-(float)($int.'.'.$float):(float)($int.'.'.$float);
                break;  
            default:
                return false; 
                break;
        }
    }
}
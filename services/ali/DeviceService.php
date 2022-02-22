<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 14:33:43
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-09 14:40:07
 * @Description: 
 */

namespace services\ali;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\models\console\iot\ali\Device;
use common\models\console\iot\ali\Value;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class DeviceService extends Service
{
    const PARMAS = 3;
    const Heartbeat = 4;
    const RESULT = 3;
    const EMPTY = 10;

    public $modelClass = Device::class;


    /**
     * 在线设备
     * 
     * @param n*o
     * @return n*o
     * @throws: 
     */    
    public function getOnLineCount()
    {
        return Value::find()
            ->where(['>=','status',StatusEnum::ENABLED])
            ->andWhere(['between','event_time',strtotime('-1 day')*1000,time()*1000])
            ->groupBy('pid')
            ->count();
    }

    public function getValue($data){
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
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 14:33:43
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 17:08:18
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
            ->andWhere(['between','event_time',strtotime('-1 day'),time()])
            ->groupBy('pid')
            ->count();
    }
}
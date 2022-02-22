<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-06 15:01:23
 * @Description: 
 */

namespace common\enums\monitor;

use common\enums\BaseEnum;
use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class ItemStepsEnum extends BaseEnum
{
    const CONTRACT = 1;
    const FOR = 2;
    const FINANCE = 3;
    const END = 4;
    const RECORD = 5;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::CONTRACT => '合同',
            self::FOR => '任务派发',
            self::FINANCE => '收款',
            self::END => '完成',
            self::RECORD => '归档',
        ];
    }


    public static function getShubmessage($key){
        $data = [
            self::CONTRACT =>  MessageReasonEnum::$actionTemplate['item_verify'][MessageActionEnum::STEPS_CONTART],
            self::FOR => MessageReasonEnum::$actionTemplate['item_verify'][MessageActionEnum::STEPS_SERVICE],
            self::FINANCE => MessageReasonEnum::$actionTemplate['item_verify'][MessageActionEnum::STEPS_MONEY],
            self::END => MessageReasonEnum::$actionTemplate['item_verify'][MessageActionEnum::STEPS_END],
        ];
        return $data[$key];
    }

    public static function getAction($key){
        $data = [
            self::CONTRACT =>  MessageActionEnum::STEPS_CONTART,
            self::FOR =>MessageActionEnum::STEPS_SERVICE,
            self::FINANCE => MessageActionEnum::STEPS_MONEY,
            self::END => MessageActionEnum::STEPS_END,
        ];
        return $data[$key];
    }


    public static function getColumn(){
        return [
            self::getValue(self::CONTRACT),
            self::getValue(self::FOR),
            self::getValue(self::FINANCE),
            self::getValue(self::END),
        ];
    }

    public static function getNext($key){
        return $key > self::RECORD
            ?'完成'
            :self::getMap()[$key+1];
    }
}
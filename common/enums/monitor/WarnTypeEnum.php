<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:52:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-09 14:21:27
 * @Description: 
 *  因
 *  
 */


namespace common\enums\monitor;

use common\enums\BaseEnum;
use common\enums\WarnEnum;

/**
 * Class AppEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class WarnTypeEnum extends BaseEnum
{

    const LOW = 1;
    const CENTRE = 2;
    const HIGH = 3;
    const HIGHEST = 4;
    const OTHER = 99;


    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::LOW => '低',
            self::CENTRE => '中',
            self::HIGH => '高',
            self::HIGHEST => '最高',
            self::OTHER => '其他'
        ];
    }

    public static function getDesc($key)
    {
        $data =  [
            self::LOW => '两层及以下，3%',
            self::CENTRE => '三层及以上，2%',
            self::HIGH => '高层24M到60M,整体倾斜大于7‰,且连续2个月大于0.5‰',
            self::HIGHEST => '高层60M到100M,整体倾斜大于5‰,且连续2个月大于0.5‰',
            self::OTHER => '其他'
        ];
        return $data[$key];
    }


    /**
     * @param {*} $key
     * @param {*} $value
     * @param {*} $option
     * @return {*}
     * @throws: $option = [ 'month' => [],'month2' =>[] ]
     * 第一、二个月份的全部数据 ，判断2个月的连续倾斜是否大于
     *
     */
    public static function getWarn($key, $value, $data = [])
    {
        $res = false;
        switch ($key) {
            case self::LOW:
                return $res = ($value > 30);
                break;
            case self::CENTRE:
                $res = $value > 20;
                break;
            case self::HIGH:
                $res = (self::getWarnArray($data) || $value > 7);
                break;
            case self::HIGHEST:
                $res = (self::getWarnArray($data) || $value > 5);
                break;
            default:
                break;
        }

        return $res;
    }

    public static function getWarnArray($data, $minNum = 0, $j = false)
    {
        // 取标位开始的30个数据
        $topData = array_slice($data, $minNum, $minNum + 30);
        $min = $max = $topData[0];
        // 循环
        for ($i = 1; $i < count($topData); $i++) {
            if (!$topData[$i]) {
                continue;
            } else {
                $min = isset($min) ? $min : $topData[$i];
                $max = isset($max) ? $max : $topData[$i];
            }
            if ($topData[$i] > $max) {
                $max = $topData[$i];
            }
            if ($topData[$i] < $min) {
                $min = $topData[$i];
            }
            // 当条件符合直接跳出循环
            if (abs($max - $min) > 0.5) {
                $minNum = $i;
                break;
            }
        }
        // 判断是否报警
        $jug = ($max - $min) > 0.5;
        // 报警和第一次循环判断
        if ($jug && !$j) {
            return self::getWarnArray($data, $minNum, true);
        }
        return $jug;
    }
};

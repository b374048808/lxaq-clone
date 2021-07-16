<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 15:42:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-28 17:47:18
 * @Description: 
 */

namespace services\monitor;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\enums\JudgeEnum;
use common\enums\WarnEnum;
use common\models\monitor\rule\Log; //规则引擎
use common\models\monitor\project\rule\Log as HouseLog; //内置房屋规则
use common\models\monitor\project\Point;
use common\models\monitor\project\rule\Item;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class RuleSimpleService extends Service
{

    /**
     * 判断监测点位是否报警
     * 
     * @param  $point_id|int
     * @param  $data|number
     * @return int
     * @throws: 
     */    
    public function getRuleWarn($point_id, $data)
    {

        $pointModel = Point::findOne($point_id);
        // 遍历当前点位的建筑物的报警触发器
        $simpleModel = Item::find()
            ->where(['pid' => $pointModel])
            ->andWhere(['type' => $pointModel['type']])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('warn ASC')
            ->asArray()
            ->all();
        $warn =  WarnEnum::SUCCESS;
        $itemId = null;
        foreach ($simpleModel as  $value) {
            $gongshi  = $data . JudgeEnum::getValue($value['judge']) . $value['value'];
            if (eval("return $gongshi;")) {
                $warn = $value['warn'] > $warn ? $value['warn'] : $warn;
                $itemId = $value['id']; //报警日志的触发器ID
            }

        }
        // 触发报警,最大报警值的触发器
        if ($warn > WarnEnum::SUCCESS) {
            $this->addPointSimpleLog($point_id, $itemId, $data);    //添加日志
        }
        return $warn;

    }

    /**
     * 添加监测点报警日志
     * 
     * @param {*} $point_id 监测点
     * @param {*} $item_id 触发器
     * @param {*} $value   数据
     * @return {*}
     * @throws:  
     */    
    public function addPointSimpleLog($point_id, $item_id, $value)
    {
        $setData = [
            'item_id' => $item_id,
            'point_id' => $point_id,
            'value' => $value,
        ];
        $model  = new HouseLog();
        if ($model->load($setData, '')) {
            return $model->save();
        }
        return false;
    }


    /**
     * 添加报警日志
     * 
     * @param $item
     * @param $pointId
     * @param $dataId
     * @return bool
     * @throws \yii\base\Exception
     */
    public function addLog($item, $pointId, $data)
    {
        $setData = [
            'item_id' => $item,
            'point_id' => $pointId,
            'value' => $data,
        ];
        $model  = new Log();
        if ($model->load($setData, '')) {
            return $model->save();
        }
        return false;
    }
}

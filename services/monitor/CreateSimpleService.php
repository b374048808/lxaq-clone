<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 15:36:32
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 11:48:50
 * @Description: 
 */

namespace services\monitor;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\enums\ValueTypeEnum;
use common\models\monitor\create\Simple;
use common\models\monitor\create\Child;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Value;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class CreateSimpleService extends Service
{
    /**
     * @param $simpleId
     * @return bool
     * @throws \yii\base\Exception
     */
    public function setArrayValue($simpleId)
    {
        $model = Simple::find()
            ->with(['point'])
            ->where(['id' => $simpleId])
            ->asArray()
            ->one();
        try {
            foreach ($model['point'] as $key => $value) {
                $valueModel = new Value();
                $valueModel->pid = $value['id'];
                $valueModel->value = $this->randomFloat($model['start_value'], $model['end_value']);
                $valueModel->event_time = time();
                $valueModel->save();
            }
        } catch (\Throwable $th) {
            throw $th;
            return false;
        }
        return true;
    }


    /**
     * 场景联动id下的所有监测点位生成数据
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */    
    public function timingRand($id)
    {
        $simpleModel = Simple::find()
            ->with('child')
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $db = Yii::$app->db;
        // 在主库上启动事务
        $transaction = $db->beginTransaction();
        try {
            foreach ($simpleModel['child'] as $key => $value) {
                $pointModel = Point::find()
                    ->with('newValue')
                    ->where(['id' => $value['point_id']])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->one();
                // 判断点位是否存在，点位最新数据是否未到时间间隔
                if (!$pointModel || $pointModel['newValue']['event_time'] + $simpleModel['interval'] * 3600 > time()) {                
                    continue;
                }
                // 时间定为最后一次数据的时间加间隔
                $start_time = isset($pointModel['newValue']['event_time']) ? $pointModel['newValue']['event_time'] + $simpleModel['interval'] * 3600 : time();
                $valueModel = new Value();
                $valueModel->pid = $value['point_id'];
                $valueModel->type = ValueTypeEnum::AUTOMATIC;
                $valueModel->value = $this->randomFloat($simpleModel['start_value'], $simpleModel['end_value']);
                $valueModel->event_time = $start_time;
                $valueModel->save();
            }
            $transaction->commit();
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return true;
    }

    /**
     * @param $childId
     * @return bool
     * @throws \yii\base\Exception
     */
    public function setOneValue($childId, $time = null)
    {
        $childModel = Child::findOne($childId);

        $model = Simple::findOne($childModel['simple_id']);
        $valueModel = new Value();
        $valueModel->pid = $childModel['point']['id'];
        $valueModel->value = $this->randomFloat($model->start_value, $model->end_value);
        $valueModel->event_time = $time ?: time();

        return $valueModel->save();
    }



    private function randomFloat($min = 0, $max = 1)
    {
        $str = $min ? (string) $min : (string) $max;
        $decimalCount = explode('.', $str);
        $decimalCount = strlen($decimalCount[1]);
        $t = $min + mt_rand() / mt_getrandmax() * ($max - $min);
        return $t;
    }
}
;
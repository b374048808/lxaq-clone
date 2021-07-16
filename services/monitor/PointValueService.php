<?php

namespace services\monitor;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\helpers\EchantsHelper;
use common\helpers\EchantsArrayHelper;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Value;
use common\enums\WarnEnum;
use common\enums\ValueStateEnum;
use common\enums\monitor\SubscriptionActionEnum;
use common\enums\monitor\SubscriptionReasonEnum;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class PointValueService extends Service
{
    public $id;

    public $type;

    public $valueType = 1;


    /**
     * 
     * 生成监测点数据
     * 
     * @param {*} $pid 监测点ID
     * @param {*} $data 数据value
     * @param {*} $time 时间
     * @return bool
     * @throws: 添加数据
     */    
    public function setValue($pid,$data,$time = null)
    {
        // 判断
        $warn = Yii::$app->services->ruleSimple->getRuleWarn($pid, $data);

        $model = new Value;
        $model->pid = $pid;
        $model->value = $data;
        $model->event_time = $time?:time();
        $model->warn = $warn;
        // 审核状态
        $model->state = $model->warn > WarnEnum::SUCCESS ? ValueStateEnum::AUDIT : ValueStateEnum::ENABLED;
        // 添加提醒
        if ($model->save() && $model->warn > WarnEnum::SUCCESS) {
            $pointModel = Point::find()
                ->with('house')
                ->where(['id' => $pid])
                ->asArray();
            $content = '监测房屋'.$pointModel['house']['title'].'下的监测点'.$pointModel['title'].'发生数据报警,数据为'.$data;
            
            Yii::$app->services->monitorNotify->createRemind(
                $model->attributes['id'],
                SubscriptionReasonEnum::BEHAVIOR_CREATE,
                SubscriptionActionEnum::OVER_TIME,
                $content,
            );
        }
        return true;
    }
    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenChartStat($type, $id, $valueType)
    {
        $pointModel = Point::findOne($id);
        $fields = [
            'value' => '数值',
        ];
        $this->id = $id;
        $this->valueType = $valueType;
        $this->type = $pointModel->type;
        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);


        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            $model = Value::class;
            return $model::find()
                ->select(['value', "from_unixtime(event_time, '$formatting') as time"])
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['type' => $this->valueType])
                ->andWhere(['pid' => $this->id])
                ->andWhere(['between', 'event_time', $start_time, $end_time])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }


    /**
     * 获取区间数据
     *
     * @param number $type
     * 
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getPointBetweenCount($type, $id, $valueType)
    {
        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['type' => $valueType])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $fields = [];
        foreach ($pointModel as $key => $value) {
            $fields = array_merge($fields,array($value['title'] => $value['title']));
        }        

        global $modelType, $modelPoints;
        $modelType = $valueType;
        $modelPoints = $pointModel;

        // 获取时间和格式化
        list($time, $format) = EchantsArrayHelper::getFormatTime($type);
        // 获取数据
        return EchantsArrayHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            global $modelType, $modelPoints;
            $res = [];
            foreach ($modelPoints as $key => $value) {
                array_push(
                    $res,
                    Value::find()
                        ->select(['max(value) as '.$value['title'], "from_unixtime(event_time, '$formatting') as time"])
                        ->where(['>', 'status', StatusEnum::DISABLED])
                        ->andWhere(['pid' => $value['id']])
                        ->andWhere(['between', 'event_time', $start_time, $end_time])
                        ->groupBy('time')
                        ->asArray()
                        ->all()
                );
            }            
            return $res;
        }, $fields, $time, $format);
    }
}

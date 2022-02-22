<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 14:09:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-22 13:29:14
 * @Description: 
 */

namespace services\ali;

use Yii;
use common\components\Service;
use common\models\console\iot\ali\Value;
use common\helpers\EchantsHelper;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class ValueService extends Service
{

    public $modelClass = Value::class;


    /**
     * 添加数据日志
     *
     * @param array $message
     */
    public function setValue($message = [])
    {
        $model = new Value();
        $model = $model->loadDefaultValues();
        $model->attributes = $message;

        return $model->save() ? $model->attributes['id'] : $model;
    }

    /**
     * 获取区间数据数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenCountStat($type)
    {
        $fields = [
            'count' => '接收(次)',
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return Value::find()
                ->select(['count(id) as count', "from_unixtime(created_at, '$formatting') as time"])
                ->andWhere(['between', 'event_time', $start_time, $end_time])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }
}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 10:01:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-05 12:35:24
 * @Description: 
 */

namespace services\ctwing;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\console\iot\huawei\Value;
use common\helpers\EchantsHelper;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class ValueService extends Service
{
    // 设备点位数据
    public $modelClass = Value::class;

    /**
     * 添加设备数据
     *
     * @param array $data
     * @return int $id
     */
    public function setValue($id, $data)
    {
        $services = $data['payload'][0];
        $model = new $this->modelClass;
        $data['serviceType'] = $services['serviceId'];
        $data['services'] = $services['payload'];
        $data['event_time'] = $data['timestamp'];
        if ($model->load($data, '')) {
            $model->pid = $id;
            $model->value = $services['serviceData'];
            return $model->save()
                ? $model->attributes['id']
                : $model;
        }
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
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->groupBy(['time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }
}

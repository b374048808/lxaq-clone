<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 14:32:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-28 21:11:28
 * @Description: 
 */

namespace services\huawei;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\models\console\iot\huawei\Device;
use common\models\console\iot\huawei\Value;
use common\helpers\EchantsHelper;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class DeviceService extends Service
{

    public $modelClass = Device::class;


    public function getOnLineCount()
    {
        return Value::find()
            ->where(['>=','status',StatusEnum::ENABLED])
            ->andWhere(['between','event_time',strtotime('-1 day'),time()])
            ->groupBy('pid')
            ->count();
    }


    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenCountStat($type)
    {
        $fields = [
            'count' => '注册会员人数',
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
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }
}

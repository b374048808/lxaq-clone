<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 14:32:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-16 16:32:10
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
            ->where(['>=', 'status', StatusEnum::ENABLED])
            ->andWhere(['between', 'event_time', strtotime('-1 day'), time()])
            ->groupBy('pid')
            ->count();
    }

    public function getUpdateLasttime()
    {
        $model = Device::find()
            ->with(['newValue'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($model as $key => $value) {
            if ($value['newValue']['event_time'] > $value['last_time']) {
                Device::updateAll(['last_time' => $value['newValue']['event_time']], ['id' => $value['id']]);
            }
            # code...
        }
        return true;
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

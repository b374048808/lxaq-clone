<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-28 20:39:00
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 15:15:32
 * @Description: 
 */

namespace services\monitor;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use common\helpers\EchantsHelper;
use common\models\monitor\project\point\Warn;
use common\models\monitor\project\Point;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class PointWarnService extends Service
{
    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenChartStat($type)
    {
        $fields = [
            'count' => '预警数',
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return Warn::find()
                ->select(['count(id) as count', "from_unixtime(created_at, '$formatting') as time"])
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->groupBy(['time'])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }


    /**
     * 根据房屋ID判断房屋报警等级
     * 
     * @param n*o $id
     * @return n*o
     * @throws: 
     */    
    public function getHouseWarn($id)
    {
        $pointIds = Point::getColumn($id);
        $model = Warn::find()
            ->where(['in','pid',$pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['state' => WarnStateEnum::AUDIT])
            ->orderBy('warn desc')
            ->asArray()
            ->one();

        return $model?$model['warn']:WarnEnum::SUCCESS;
    }

    /**
     * 根据监测点ID判断房屋报警等级
     * 
     * @param n*o $id
     * @return n*o
     * @throws: 
     */    
    public function getPointWarn($id)
    {
        $model = Warn::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['state' => WarnStateEnum::AUDIT])
            ->orderBy('warn desc')
            ->asArray()
            ->one();

        return $model?$model['warn']:WarnEnum::SUCCESS;
    }
}

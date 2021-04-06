<?php

namespace services\monitor;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\helpers\EchantsHelper;
use common\enums\PointEnum;
use common\helpers\ArrayHelper;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Angle;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class PointValueService extends Service
{
    public $id;

    public $type;
    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenChartStat($type, $id)
    {
        $fields = [
            'count' => '数值',
        ];
        $this->id = $id;

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);

        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            $model = PointEnum::getModel($this->id);
            return $model::find()
                ->select(['value as count', "from_unixtime(event_time, '$formatting') as time"])
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['pid' => $this->id])
                ->andWhere(['between', 'event_time', $start_time, $end_time])
                ->groupBy(['event_time'])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }

    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getHouseCountStat($type, $id, $pointType)
    {
        $pointModel = Point::findOne($id);
        $models = Point::find()
            ->where(['>', 'status', StatusEnum::DISABLED])
            ->andWhere(['pid' => $pointModel->pid])
            ->asArray()
            ->all();
        $fields = [];
        foreach ($models as $key => $value) {
            $fields=  array_merge($fields,[$value['title'] => $value['title']]);
        }
        $this->id = $id;
        $this->type = $pointType;
        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);

        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {

            $model = PointEnum::getModel($this->id);
            $pointModel = Point::findOne($this->id);
            $models = Point::find()
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['pid' => $pointModel->pid])
                ->asArray()
                ->all();
            $series = [];
            foreach ($models as $key => $value) {
                $data = $model::find()
                    ->select(['value ' . $value['title'], "from_unixtime(event_time, '$formatting') as time"])
                    ->where(['>', 'status', StatusEnum::DISABLED])
                    ->andWhere(['pid' => $value['id']])
                    ->andWhere(['between', 'event_time', $start_time, $end_time])
                    // ->groupBy(['pid'])
                    ->asArray()
                    ->all();
                $series = array_merge($series, $data);               
            }
            // var_dump($series);exit;
            return $series;
        }, $fields, $time, $format);
    }
}

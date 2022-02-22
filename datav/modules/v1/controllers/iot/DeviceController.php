<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:42:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-29 14:33:58
 * @Description: 
 */

namespace datav\modules\v1\controllers\iot;

use common\enums\StatusEnum;
use common\models\console\iot\ali\Device as AliDevice;
use common\models\console\iot\ali\Value as AliValue;
use common\models\console\iot\huawei\Device;
use common\models\console\iot\huawei\Value as HuaweiValue;
use yii;
use datav\controllers\OnAuthController;
use common\models\monitor\project\point\Value;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class DeviceController extends OnAuthController
{
    public $modelClass = Value::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index', 'get-day-chart'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $info = [];
        $huaweiOnline = HuaweiValue::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['>', 'event_time', strtotime('-1 day')])
            ->count();
        $aliOnline = AliValue::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['>', 'event_time', strtotime('-1 day') * 1000])
            ->count();
        
        $huaweiDevice = Device::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        $aliDevice = AliDevice::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        array_push($info,[
            'title' => '倾斜设备',
            'online'    => $huaweiOnline,
            'all'   => $huaweiDevice,
        ]);
        array_push($info,[
            'title' => '裂缝设备',
            'online'    => $aliOnline,
            'all'   => $aliDevice,
        ]);

        return $info;
    }

    /**
     * 当月每天设备接收数据次数对比昨天
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionGetDayChart()
    {
        $day  = date('t');
        $info = [];
        for ($i = 1; $i < $day; $i++) {
            // 本月
            $start_time = strtotime(date('Y-m-' . $i . ' 00:00:00'));
            $huaweiCount = HuaweiValue::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['between', 'event_time', $start_time, strtotime('+1 day', $start_time)])
                ->groupBy('pid')
                ->count();
            // 阿里时间需要*1000
            $aliCount = AliValue::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['between', 'event_time', $start_time * 1000, strtotime('+1 day', $start_time) * 1000])
                ->groupBy('pid')
                ->count();
            array_push($info, [
                's' => "bar",
                'x' => date('j', $start_time),
                'y' => $huaweiCount + $aliCount
            ]);
            // 上月
            $start_time = strtotime('-1 month', strtotime(date('Y-m-' . $i . ' 00:00:00')));
            $huaweiCount = HuaweiValue::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['between', 'event_time', $start_time, strtotime('+1 day', $start_time)])
                ->groupBy('pid')
                ->count();
            // 阿里时间需要*1000
            $aliCount = AliValue::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['between', 'event_time', $start_time * 1000, strtotime('+1 day', $start_time) * 1000])
                ->groupBy('pid')
                ->count();
            array_push($info, [
                's' => "bar2",
                'x' => date('j', $start_time),
                'y' => $huaweiCount + $aliCount
            ]);
        }
        return $info;
    }
}

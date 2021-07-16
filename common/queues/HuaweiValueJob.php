<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-21 10:10:19
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-28 18:33:02
 * @Description: 
 */

namespace common\queues;

use Yii;
use yii\base\BaseObject;
use common\models\console\iot\huawei\Device;
use common\models\monitor\project\point\HuaweiMap;
use common\enums\StatusEnum;
use common\enums\AxisEnum;

/**
 * Class LogJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class HuaweiValueJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * 华为设备记录数据
     *
     * @var
     */
    public $data;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\base\InvalidConfigException
     */
    public function execute($queue)
    {
        $data = $this->data;
        $iotId = $data['deviceId'];
        $services = $data['services'][0];

        $time = strtotime($services['eventTime']);
        // 根据deviceId查询系统内设备
        $deviceModel = Device::find()
            ->where(['device_id' => $iotId])
            ->asArray()
            ->one();
        if ($deviceModel) {
            $deviceId = $deviceModel['id'];

            // 设备数据记录成功
            Yii::$app->services->huaweiValue->setValue($deviceId, $data);

            // 遍历所有设备绑定的点位
            $models = HuaweiMap::find()
                ->with('point')
                ->where(['device_id' => $deviceId]) //关联设备ID
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->asArray()
                ->all();
            foreach ($models as $key => $value) {
                // 判断绑定的角度
                $axisData = $data[AxisEnum::getAxisValue($value['axis'])];
                // 角度数据转化为倾斜率+监测点初始数据
                $axisValue = tan($axisData * 0.017453293) * 1000+isset($value['point']['initial_value'])?$value['point']['initial_value']:0; //单位千分之，PHPtan算的是弧度，需要角度*0.017453293      
                // 监测点添加数据
                Yii::$app->services->pointValue->getDeviceValue($value['point_id'], $axisValue, $time);
            }
            return true;
        }
        // 没有查询到设备，返回错误
        
        return false;
    }
}

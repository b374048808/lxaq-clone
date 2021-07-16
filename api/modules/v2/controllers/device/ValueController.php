<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 09:08:35
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 10:35:55
 * @Description: 
 */

namespace api\modules\v2\controllers\device;

use Yii;
use api\controllers\OnAuthController;
use common\queues\HuaweiValueJob;
use common\models\console\iot\huawei\Device;
use common\models\monitor\project\point\HuaweiMap;
use common\enums\StatusEnum;
use common\enums\AxisEnum;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ValueController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['value'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionValue()
    {
        $request = Yii::$app->request;
        $data = $request->post();

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
            foreach ($models as $value) {
                // 判断绑定的角度
                $axisData = $services['data'][AxisEnum::getAxisValue($value['axis'])];
                // 角度数据转化为倾斜率+监测点初始数据
                $axisValue = tan($axisData * 0.017453293) * 1000+(isset($value['point']['initial_value'])?$value['point']['initial_value']:0); //单位千分之，PHPtan算的是弧度，需要角度*0.017453293      
                // 监测点添加数据
                Yii::$app->services->pointValue->setValue($value['point_id'], $axisValue, $time);
            }
            return true;
        }
        // 没有查询到设备，返回错误
        
        return false;
        
        // return Yii::$app->queue->push(new HuaweiValueJob([
        //     'data' => $data
        // ]));
        
    }
}

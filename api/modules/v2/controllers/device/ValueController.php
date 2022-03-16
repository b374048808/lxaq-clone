<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 09:08:35
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-10 15:16:13
 * @Description: 
 */

namespace api\modules\v2\controllers\device;

use Yii;
use api\controllers\OnAuthController;
use common\models\console\iot\huawei\Device;
use common\models\monitor\project\point\HuaweiMap;
use common\enums\StatusEnum;
use common\enums\AxisEnum;
use common\enums\device\SwitchEnum;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

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
        // 根据deviceId查询系统内设备,
        $deviceModel = Device::find()
            ->where(['device_id' => $iotId])
            ->asArray()
            ->one();
        Device::updateAll(['last_time' => $time], ['device_id' => $iotId]);

        if ($deviceModel) {
            // 添加设备数据
            $deviceId = $deviceModel['id'];
            // 设备数据记录成功
            Yii::$app->services->huaweiValue->setValue($deviceId, $data);
            if ($deviceModel['switch'] == SwitchEnum::DISABLED) {
                return true;
                # code...
            }
            Yii::$app->services->huaweiValue->warnSend($deviceModel['number'], $data);

            if ($services['serviceId'] == 'Alarm' || $services['serviceId'] == 'Heartbeat') {
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
                    // 角度数据转化为倾斜率，判断正反面+监测点初始数据
                    $axisValue = tan($axisData * 0.017453293) * 1000 * $value['is_up'] + (isset($value['point']['initial_value']) ? $value['point']['initial_value'] : 0); //单位千分之，PHPtan算的是弧度，需要角度*0.017453293      
                    // 点位数据添加
                    Yii::$app->services->pointValue->setValue($value['point_id'], $axisValue, $time);
                }
                return true;
            }
        }
        // 没有查询到设备，返回错误

        return false;
    }
}

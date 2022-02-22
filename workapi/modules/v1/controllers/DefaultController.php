<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-05 11:58:01
 * @Description: 
 */

namespace workapi\modules\v1\controllers;

use workapi\controllers\OnAuthController;
use Yii;
use common\helpers\WorkerAuth;
use yii\httpclient\Client;
use EasyWeChat\Factory;
use common\queues\HuaweiValueJob;
use common\models\console\iot\huawei\Device;
use common\models\monitor\project\point\HuaweiMap;
use common\enums\StatusEnum;
use common\enums\AxisEnum;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package workapi\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class DefaultController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index', 'search'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $permissionName = '/' . Yii::$app->controller->route;
        // 开始权限校验
        if (!WorkerAuth::verify($permissionName)) {
            throw new \yii\web\BadRequestHttpException('对不起，您现在还没获此操作的权限');
        }
        return true;
    }

    /**
     * 测试查询方法
     *
     * 注意：该方法在 main.php 文件里面的 extraPatterns 单独配置过才正常访问
     *
     * @return string
     */
    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionSearch()
    {
        $request = Yii::$app->request;
        $data = $request->post();

        // $data = file_get_contents("php://input");

        $file = '/www/wwwroot/lhsafe.com.cn/lxaq/workapi/modules/v1/controllers/iot_push_log/data.txt';
        // 	file_put_contents($file,'input'."\n".$data.date('Y-m-d H:i:s')."\n", FILE_APPEND);
        file_put_contents($file, 'post' . "\n" . $data . "\n" . date('Y-m-d H:i:s') . "\n", FILE_APPEND);

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
                    // 角度数据转化为倾斜率+监测点初始数据
                    $axisValue = tan($axisData * 0.017453293) * 1000 + (isset($value['point']['initial_value']) ? $value['point']['initial_value'] : 0); //单位千分之，PHPtan算的是弧度，需要角度*0.017453293      
                    // 监测点添加数据
                    Yii::$app->services->pointValue->setValue($value['point_id'], $axisValue, $time);
                }
                return true;
            }
        }
        // 没有查询到设备，返回错误

        return false;
    }
}

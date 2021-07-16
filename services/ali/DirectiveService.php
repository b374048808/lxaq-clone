<?php

namespace services\ali;

use Yii;
use common\components\Service;
use common\models\console\iot\ali\Directive;
use common\models\console\iot\ali\DirectiveLog;
use common\helpers\ali\Signal;
use common\helpers\CRC16;
use common\models\console\iot\ali\Device;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;
use common\helpers\ali\IotHelper;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class DirectiveService extends Service
{

    public $modelClass = Directive::class;

    public function send($deviceId, $directiveId, $value = '')
    {
        $deviceModel = Device::findOne($deviceId);
        $directiveModel = Directive::findOne($directiveId);

        $params =    [
            'RegionId' => "cn-shanghai",
            'TopicFullName' => "/" . $deviceModel->product->product_key . "/" . $deviceModel->device_id . "/user/get",
            'MessageContent' => base64_encode($directiveModel->content),
            //  'MessageContent' => base64_encode("@DTU:0000:POLL:1,60,1"),
            'ProductKey' => $deviceModel->product->product_key,
        ];
        try {
            if ($value) {
                $directiveModel->content = str_replace('@RES', $value, $directiveModel->content);
            }      
            $device_success = IotHelper::iot('Pub', $params);
            $this->saveLog([
                'device_id' => $deviceId,
                'params' => ['RES' => $value],
                'directive_id' => $directiveId,
                'content' => $directiveModel->content,
                'ip' => Yii::$app->request->userIP,
                'results' => $device_success,
            ]);
            return $device_success['data']['Success'];
        } catch (NotFoundHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }

    /**
     * @param array $data
     * @return SmsLog
     */
    protected function saveLog($data = [])
    {
        $log = new DirectiveLog();
        $log = $log->loadDefaultValues();
        $log->attributes = $data;
        $log->save();

        return $log;
    }
}

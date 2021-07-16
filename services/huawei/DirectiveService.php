<?php

namespace services\huawei;

use Yii;
use common\components\Service;
use common\enums\StatusEnum;
use common\models\console\iot\huawei\Directive;
use common\models\console\iot\huawei\DirectiveLog;
use common\helpers\huawei\Signal;
use common\helpers\CRC16;
use common\models\console\iot\huawei\Device;
use common\models\console\iot\huawei\Value;
use yii\web\NotFoundHttpException;
use yii\web\UnprocessableEntityHttpException;

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
        try {
            if ($value) {
                $valueStrpad = $this->getStrpad($value);
                $directiveModel->content = str_replace('@RES', $valueStrpad, $directiveModel->content);
                $directiveModel->content .= CRC16::hex($directiveModel->content);
            }            
            $message = Signal::postData($deviceModel->device_id, $directiveModel->content);

            $this->saveLog([
                'device_id' => $deviceId,
                'params' => ['RES' => $value],
                'directive_id' => $directiveId,
                'content' => $directiveModel->content,
                'ip' => Yii::$app->request->userIP,
                'results' => json_encode($message),
            ]);
            return true;
        } catch (NotFoundHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        }
        return false;
    }

    /*
	* 分钟*60转化为秒
	* 在分2位颠倒顺序,补偿0到8位
	*/
    public function getStrpad($string)
    {
        $string = dechex($string);
        $res = '';
        for ($i = strlen($string); $i > 0; $i -= 2) {
            if ($i < 2) {
                $res .= str_pad(substr($string, 0, 1), 2, '0', STR_PAD_LEFT);
            } else {
                $res .= substr($string, $i - 2, 2);
            }
        }
        return str_pad($res, 8, '0');
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

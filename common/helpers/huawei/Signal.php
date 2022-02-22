<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-21 13:36:53
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-21 08:59:07
 * @Description: 
 */

namespace common\helpers\huawei;

use DeviceManagementTest;
use Yii;
use North_PHP_SDK\client\invokeapi\SignalDelivery;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\PostDeviceCommandInDTO;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\dto\CommandDTOV4;
use North_PHP_SDK\client\invokeapi\DeviceManagement;
use yii\web\NotFoundHttpException;

class Signal
{

    public $northApiClient;


    public function init()
    {
        /**---------------------initialize northApiClient------------------------*/
        $this->northApiClient = AuthUtil::initApiClient();
    }

    public static function getToken()
    {
        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication(AuthUtil::initApiClient());
        $authOutDTO = $authentication->getAuthToken();
        $session = \Yii::$app->session;
        $session->set('accessToken', $authOutDTO->accessToken);
    }



    public static function postData($deviceId, $value, $i = 0)
    {
        $signalDelivery = new SignalDelivery(AuthUtil::initApiClient());
        $session = Yii::$app->session;
        $pdcInDTO = new PostDeviceCommandInDTO();
        $pdcInDTO->deviceId = $deviceId;
        $cmd = new CommandDTOV4();
        $cmd->serviceId = "Settings";
        $cmd->method = "SET_DEVICE_PARAMS"; //"SYNCHRONIZE_INFO" is the command name defined in the profile
        $cmdParam = array("value" => $value); //"cda123" is the command parameter name defined in the profile
        $cmd->paras = $cmdParam;
        $pdcInDTO->command = $cmd;
        try {
            return $signalDelivery->postDeviceCommand($pdcInDTO, null, $session->get('accessToken'));
            //  true;
        } catch (NorthApiException $e) {
            if ($i ==  0) {
                self::getToken();
                self::postData($deviceId, $value, $i = 1);
            } else {
                throw new NotFoundHttpException($e);
            }
        }
        return null;
    }


    public static function sendAll($ids, $content)
    {
        $signalDelivery = new SignalDelivery(AuthUtil::initApiClient());
        $session = Yii::$app->session;
        try {
            $pdc = new PostDeviceCommandInDTO();
            foreach ($ids as  $value) {
                $pdcInDTO = clone $pdc;
                $pdcInDTO->deviceId = $value;
                $cmd = new CommandDTOV4();
                $cmd->serviceId = "Settings";
                $cmd->method = "SET_DEVICE_PARAMS"; //"SYNCHRONIZE_INFO" is the command name defined in the profile
                $cmdParam = array("value" => $content); //"cda123" is the command parameter name defined in the profile
                $cmd->paras = $cmdParam;
                $pdcInDTO->command = $cmd;
                return $signalDelivery->postDeviceCommand($pdcInDTO, null, $session->get('accessToken'));
            }
            //  true;
        } catch (NorthApiException $e) {
            throw new NotFoundHttpException($e);
        }
        return null;
    }
}

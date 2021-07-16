<?php

namespace common\helpers\huawei;

use Yii;
use North_PHP_SDK\client\invokeapi\SignalDelivery;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\dto\PostDeviceCommandInDTO;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\dto\CommandDTOV4;
use North_PHP_SDK\client\dto\UpdateDeviceCommandInDTO;
use North_PHP_SDK\client\dto\QueryDeviceCommandInDTO;
use North_PHP_SDK\client\dto\CreateDeviceCmdCancelTaskInDTO;
use North_PHP_SDK\client\dto\QueryDeviceCmdCancelTaskInDTO;

class Signal
{
    public static function postData($deviceId, $value)
    {
        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $signalDelivery = new SignalDelivery($northApiClient);
        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;
        /**---------------------发布一个NB-IoT设备命令------------------------*/
        //this is a test NB-IoT device
        $pdcOutDTO = Signal::postCommand($signalDelivery, $deviceId, $accessToken, $value);
        return empty($pdcOutDTO) ? null : $pdcOutDTO;
    }
    private static function postCommand($signalDelivery, $deviceId, $accessToken, $value)
    {
        $pdcInDTO = new PostDeviceCommandInDTO();
        $pdcInDTO->deviceId = $deviceId;
        $cmd = new CommandDTOV4();
        $cmd->serviceId = "Settings";
        $cmd->method = "SET_DEVICE_PARAMS"; //"SYNCHRONIZE_INFO" is the command name defined in the profile
        $cmdParam = array("value" => $value); //"cda123" is the command parameter name defined in the profile
        $cmd->paras = $cmdParam;
        $pdcInDTO->command = $cmd;
        try {
            return $signalDelivery->postDeviceCommand($pdcInDTO, null, $accessToken);
        } catch (NorthApiException $e) {
            echo $e;
        }
        return null;
    }
}

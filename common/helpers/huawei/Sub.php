<?php

namespace common\helpers\huawei;

use Yii;
use North_PHP_SDK\client\invokeapi\Authentication;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\invokeapi\SubscriptionManagement;
use  North_PHP_SDK\client\dto\SubDeviceDataInDTO;
use  North_PHP_SDK\client\dto\SubDeviceManagementDataInDTO;
use  North_PHP_SDK\client\dto\QueryBatchSubInDTO;
use  North_PHP_SDK\client\dto\DeleteBatchSubInDTO;

class Sub
{
    public static function device()
    {
        \North_PHP_SDK\utils\PropertyUtil::init();
        $appID = \North_PHP_SDK\utils\PropertyUtil::getProperty('appId');
        /**---------------------initialize northApiClient------------------------*/
        $northApiClient = AuthUtil::initApiClient();
        $subscriptionManagement = new SubscriptionManagement($northApiClient);
        /**---------------------get accessToken at first------------------------*/
        $authentication = new Authentication($northApiClient);
        $authOutDTO = $authentication->getAuthToken();
        $accessToken = $authOutDTO->accessToken;
        /**---------------------sub  通知------------------------*/
        $callbackUrl = "https://huawei.lhsafe.com.cn:443/index.php"; //this is a test callbackUrl
        $subDTO = Sub::subDeviceData(
            $subscriptionManagement,
            "deviceDatasChanged",
            $callbackUrl,
            $accessToken
        );
    }
    public static function subDeviceData($subscriptionManagement, $notifyType, $callbackUrl, $accessToken)
    {
        $sddInDTO = new SubDeviceDataInDTO();
        $sddInDTO->notifyType = $notifyType;
        $sddInDTO->callbackUrl = $callbackUrl;
        try {
            $subDTO = $subscriptionManagement->subDeviceData($sddInDTO, null, $accessToken);
            echo $subDTO . "\r\n";
            return $subDTO;
        } catch (NorthApiException $e) {
            echo $e . "\r\n";
        }
        return null;
    }
}

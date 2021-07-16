<?php

namespace common\helpers\huawei;

use North_PHP_SDK\client\dto\AuthOutDTO;
use North_PHP_SDK\client\dto\ClientInfo;
use North_PHP_SDK\client\DefaultNorthApiClient;
use North_PHP_SDK\client\NorthApiClient;
use North_PHP_SDK\client\NorthApiException;
use North_PHP_SDK\client\ClientService;
use North_PHP_SDK\utils\PropertyUtil;

class AuthUtil
{
    private static $northApiClient = null;
    public static function initApiClient()
    {
        if (self::$northApiClient !== null) {
            return self::$northApiClient;
        }
        self::$northApiClient = new NorthApiClient();
        PropertyUtil::init();
        $clientInfo = new ClientInfo();
        $clientInfo->platformIp = PropertyUtil::getProperty("platformIp");
        $clientInfo->platformPort = PropertyUtil::getProperty("platformPort");
        $clientInfo->appId = PropertyUtil::getProperty("appId");
        $clientInfo->secret = PropertyUtil::getProperty("secret");
        try {
            self::$northApiClient->clientInfo = $clientInfo;
            self::$northApiClient->initSSLConfig();
        } catch (NorthApiException $nae) {
            echo $nae;
        }
        return self::$northApiClient;
    }
}

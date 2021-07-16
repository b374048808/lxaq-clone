<?php

namespace common\helpers\ali;

use Yii;
use AlibabaCloud\Client\AlibabaCloud;
use AlibabaCloud\Client\Exception\ClientException;
use AlibabaCloud\Client\Exception\ServerException;

/**
 * Class Auth
 * @package common\helpers
 * @author jianyan74 <751393839@qq.com>
 */
class IotHelper
{
    public static function iot($type, $params)
    {
        AlibabaCloud::accessKeyClient(Yii::$app->params['AccessKeyId'], Yii::$app->params['AccessKeySecret'])
            ->regionId('cn-hangzhou') // 请替换为自己的 Region ID
            ->asGlobalClient();
        try {
            $result = AlibabaCloud::rpc()
                ->product('Iot')
                // ->scheme('https') // https | http
                ->version('2018-01-20')
                ->action($type)
                ->method('POST')
                ->host('iot.cn-shanghai.aliyuncs.com')
                ->options([
                    'query' => $params
                ])
                ->request();
            $code = true;
            $arr = $result->toArray();
        } catch (ClientException $e) {
            $code = false;
            $arr = $e->getErrorMessage() . PHP_EOL;
        } catch (ServerException $e) {
            $code = false;
            $arr = $e->getErrorMessage();
        }
        return $data = array('code' => $code, 'data' => $arr);
    }
}

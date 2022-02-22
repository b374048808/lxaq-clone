<?php

namespace console\controllers;

use Yii;
use yii\console\Controller;
use common\queues\AliValueJob;
use Stomp\Exception\StompException;
use Stomp\Network\Observer\Exception\HeartbeatException;
/*阿里iotAMQP通讯*/
use Stomp\Client;
use Stomp\StatefulStomp;
use yii\helpers\Console;

/**
 * 定时任务历史消息清理
 *
 * Class IotController
 * @package console\controllers
 */
class AliController extends Controller
{

    public function start_consume()
    {
        //参数说明，请参见AMQP客户端接入说明文档。
        $accessKey = "LTAI4FdTxTq6dod1WjWMAxbp";
        $accessSecret = "0fXsodw45gUHuK4jpqOcKlh7skBPa4";
        $consumerGroupId = "DEFAULT_GROUP";
        //iotInstanceId：购买的实例请填写实例ID，公共实例请填空字符串""。
        $iotInstanceId = "";
        $timeStamp = round(microtime(true) * 1000);
        //签名方法：支持hmacmd5，hmacsha1和hmacsha256。
        $signMethod = "hmacsha1";
        $clientId = "18257759115";
        //userName组装方法，请参见AMQP客户端接入说明文档。
        //若使用二进制传输，则userName需要添加encode=base64参数，服务端会将消息体base64编码后再推送。具体添加方法请参见下一章节“二进制消息体说明”。
        $userName = $clientId . "|authMode=aksign"
            . ",signMethod=" . $signMethod
            . ",timestamp=" . $timeStamp
            . ",authId=" . $accessKey
            . ",iotInstanceId=" . $iotInstanceId
            . ",consumerGroupId=" . $consumerGroupId
            . "|";
        $signContent = "authId=" . $accessKey . "&timestamp=" . $timeStamp;
        //计算签名，password组装方法，请参见AMQP客户端接入说明文档。
        $password = base64_encode(hash_hmac("sha1", $signContent, $accessSecret, $raw_output = TRUE));
        //接入域名，请参见AMQP客户端接入说明文档。
        $client = new Client('ssl://1625938628804165.iot-amqp.cn-shanghai.aliyuncs.com:61614');
        $sslContext = ['ssl' => ['verify_peer' => true, 'verify_peer_name' => false],];
        $client->getConnection()->setContext($sslContext);

        //心跳设置，需要云端每10s发送一次心跳包。
        $client->setHeartbeat(0, 10000);
        $client->setLogin($userName, $password);
        try {
            $client->connect();
        } catch (StompException $e) {
            echo "failed to connect to server, msg:" . $e->getMessage(), PHP_EOL;
        }
        //无异常时继续执行。
        $stomp = new StatefulStomp($client);
        $stomp->subscribe('/topic/#');
        return $stomp;
    }

    public function actionStomp()
    {

        $stomp = $this->start_consume();

        while (true) {
            if ($stomp == null || !$stomp->getClient()->isConnected()) {
                echo "connection not exists, will reconnect after 10s.", PHP_EOL;
                sleep(10);
                $stomp = $this->start_consume();
            }

            try {
                //处理消息业务逻辑。
                $data = $stomp->read();
                // 读取destination是否有数据，取第二位设备标识判断
                // $destination = $data['destination'];
                // $str = $destination?explode('/',$destination):[];
                // //如果不为空，解析数据添加

                if (!empty($data->body)) {
                    Console::stdout($data. "\n" . date('m-d H:i:s') . "\n");
                    // 添加入阿里消息处理队列
                    Console::stdout(strlen($data->body));
                    Yii::$app->queue->push(new AliValueJob([
                        'message' => $data,
                        'data'  => $data->body,
                    ]));
                }
                // echo $str;
            } catch (HeartbeatException $e) {
                echo 'The server failed to send us heartbeats within the defined interval.', PHP_EOL;
                $stomp->getClient()->disconnect();
            } catch (\Exception $e) {
                echo 'process message occurs error ' . $e->getMessage(), PHP_EOL;
            }
        }
    }
}

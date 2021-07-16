<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-21 10:37:18
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-01 18:42:26
 * @Description: 阿里数据解析添加
 */
namespace common\queues;

use Yii;
use yii\helpers\Console;
use yii\base\BaseObject;
use common\models\console\iot\ali\Product;
use common\models\console\iot\ali\Device;
use common\enums\AliValueTypeEnum;
use common\models\monitor\project\point\AliMap;

class AliValueJob extends BaseObject implements \yii\queue\JobInterface
{
	public $data;   //body内数据
	
	public $message;    //整体数据

	
	public function execute($queue)
	{
        $message = $this->message;
        $destination = $message['destination'];     //消息
        $eventTime = $message['generateTime']/1000;     //时间戳需要除1000
        $device_id = $destination ? explode('/', $destination) : [];
        $productKey = $device_id[1];
        $deviceId = $device_id[2];
        //如果不为空，解析数据添加
        if (empty($device_id[2])) {
            return false;
        }
        $productModel = Product::findOne(['product_key' => $productKey]);

        $deviceModel = Device::find()
            ->where(['device_id' => $deviceId])
            ->andWhere(['pid' => $productModel['id']])
            ->asArray()
            ->one();
        // 查询不到设备数据跳出
        if (!$deviceModel) {
            return false;
        }
        
        $deviceValue = AliValueTypeEnum::onValue($this->data);    //数据解析设备数据
        // 有设置初始值-当前设备数据
        $pointValue = $deviceModel['start_data']?$deviceModel['start_data'] - $deviceValue:$deviceValue;
        // 所有关联监测点
        $pointIds = AliMap::getPointColumn($deviceModel['id']);
        // 所有监测点添加数据
        try {
            foreach ($pointIds as $value) {
                Yii::$app->services->pointValue->setValue($value, $pointValue, $eventTime);
            }
            //code...
        } catch (\Throwable $th) {
            throw $th;
        }
        // 设备数据添加
        $data = [
            'pid' => $deviceModel['id'],
            'message' => json_encode($message),
            'qos' => $message['qos'],
            'destination' => $message['destination'],
            'message_id' => $message['message-id'],
            'topic' => $message['topic'],
            'subscription' => $message['subscription'],
            'event_time' => $message['generateTime'],
            'body' => $this->data,
        ];
        // 数据是否添加
        Yii::$app->services->aliValue->setValue($data);
        
	}
}

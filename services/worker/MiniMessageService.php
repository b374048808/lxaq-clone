<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 08:53:03
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-08 09:33:59
 * @Description: 
 */

namespace services\worker;

use Yii;
use common\components\Service;
use common\enums\mini\MessageReasonEnum;
use common\models\worker\MiniMessage;
use common\models\worker\MiniMessageLog;
use yii\web\NotFoundHttpException;
use EasyWeChat\Factory;

/**
 * Class NotifyPullTimeService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class MiniMessageService extends Service
{

    public function findApp()
    {
        $config = [
            'app_id' => 'wxb96b6aff1be79c6f',
            'secret' => '21f28ad371a719651fd51c1d78c422ce',

            // 下面为可选项
            // 指定 API 调用返回结果的类型：array(default)/collection/object/raw/自定义类名
            'response_type' => 'array',

            'log' => [
                'level' => 'debug',
                'file' => __DIR__ . '/wechat.log',
            ],
        ];

        // $accessToken = $app->access_token;
        // $token = $accessToken->getToken(); // token 数组  token['access_token'] 字符串
        // $token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.

        // $token = $token['access_token'];
        return Factory::miniProgram($config);
    }
    /**
     * 创建提醒
     *
     * @param int $member_id 接受者
     * @param int $target_id 触发id
     * @param string $targetType 触发类型
     * @param string $action 提醒关联动作
     * @param int $sender_id 发送者(用户)id
     * @param string $content 内容
     */
    public function createRemind($open_id, $target_id, $action, $target_type)
    {
        $model = new MiniMessage();
        $model->target_id = $target_id;
        $model->open_id = $open_id;
        $model->member_id = Yii::$app->user->identity->member_id;
        $model->action = $action;
        $model->target_type  = $target_type;
        $model->save();
    }

    public function send($id, $data, $page = NULL)
    {
        $app = $this->findApp();
        $model = MiniMessage::findOne($id);
        // 对应模版
        $template = MessageReasonEnum::$actionTemplate[$model['target_type']][$model['action']];

        $data = [
            'template_id' => $template, // 所需下发的订阅模板id
            'touser' => $model->open_id,     // 接收者（用户）的 openid
            'page' => $page,       // 点击模板卡片后的跳转页面，仅限本小程序内的页面。支持带参数,（示例index?foo=bar）。该字段不填则模板无跳转。
            'data' => $data
        ];


        $res = $app->subscribe_message->send($data);
        $model->is_read = 1;
        $model->save();
        return $this->addLog($res,$data, $id);
    }

    public function addLog($data,$messageData, $pid)
    {
        $model = new MiniMessageLog();
        $model->ip = isset(Yii::$app->request->userIP)?Yii::$app->request->userIP:'';
        $model->message_data = $messageData;
        $model->error_msg =  (string)$data['msgid'];
        $model->error_code = $data['errcode'];
        $model->error_data = $data['errmsg'];
        $model->use_time = time();
        $model->member_id = isset(Yii::$app->user->identity->member_id)?Yii::$app->user->identity->member_id:'';
        $model->pid  = $pid;
        return $model->save();
    }
}

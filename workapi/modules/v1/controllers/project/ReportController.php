<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-17 08:52:25
 * @Description: 
 */

namespace workapi\modules\v1\controllers\project;

use common\enums\company\SubscriptionActionEnum;
use common\enums\company\SubscriptionReasonEnum;
use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\monitor\ReportEnum;
use common\models\monitor\project\house\Report;
use workapi\controllers\OnAuthController;
use common\enums\StatusEnum;
use yii\web\NotFoundHttpException;
use common\enums\VerifyEnum;
use common\helpers\ArrayHelper;
use common\models\monitor\project\house\ReportMember;
use common\models\worker\MiniMessage;
use common\models\worker\Worker;
use yii\web\UnprocessableEntityHttpException;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package workapi\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ReportController extends OnAuthController
{
    public $modelClass = Report::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['rbac'];

    /**
     * 列表
     * 
     * @param {*} $start
     * @param {*} $limit
     * @return {*}
     * @throws: 
     */
    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);
        $audit = $request->get('audit', NULL);
        $sort = $request->get('sort', NULL);
        $tabbar = $request->get('tabbar', NULL);

        $order = 'id desc';
        switch ($sort) {
            case 1:
                $order = 'created_at desc';
                break;
            case 2:
                $order = 'number desc';
                break;
            case 3:
                $order = 'verify desc';
                break;

            default:
                break;
        }
        $where = [];
        switch ($tabbar) {
            case 'my':
                $where = ['user_id' => Yii::$app->user->identity->member_id];
                break;
            case 'verify':
                $where = [
                    'and',
                    ['verify' => VerifyEnum::WAIT],
                    ['verify_member' => Yii::$app->user->identity->member_id]
                ];
                break;

            default:
                # code...
                break;
        }

        $model = Report::find()
            ->with(['user', 'verifyMember'])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['file_name' => $title])
            ->andFilterWhere(['verify' => $audit])
            ->orderBy($order)
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['verify_text'] = VerifyEnum::getValue($value['verify']);
        }
        unset($value);

        return $model;
    }

    // 权限判断
    public function actionRbac()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);

        $member_id = Yii::$app->user->identity->member_id;
        $audit_rbac = false;
        $verify_rbac = false;
        $model = $this->findModel($id);
        if (in_array($member_id, Yii::$app->params['adminAccount']) || $member_id == $model['verify_member']) {
            $verify_rbac =  $model->verify == VerifyEnum::WAIT;
            # code...
        }
        $audit_rbac = ($model->user_id == $member_id);
        return [
            'audit_rbac' => $audit_rbac,
            'verify_rbac'    => $verify_rbac,
        ];
    }

    /**
     * 待审核列表
     * 
     * @param {*} $start
     * @param {*} $limit
     * @return {*}
     * @throws: 
     */
    public function actionWaitList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $number = $request->get('number', NULL);

        $model = Report::find()
            ->with(['user', 'verifyMember' => function ($queue) {
                $queue->andWhere(['verify' => VerifyEnum::WAIT]);
            }])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['verify' => VerifyEnum::WAIT])
            ->andFilterWhere(['number' => $number])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            $value['manager']  = $value['user']['realname'];
            $value['created_at'] = date('Y-m-d H:i:s');
            $value['verify_text'] = VerifyEnum::getValue($value['verify']);
        }
        unset($value);

        return $model;
    }

    public function actionView($id)
    {
        $model = Report::find()
            ->with(['house', 'user', 'verifyMemberList'])
            ->where(['id' => $id])
            ->asArray()
            ->one();

        return $model;
    }

    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $model = $id ? $this->findModel($id) : new Report();
        $member_id = Yii::$app->user->identity->member_id;
        $message_data = $request->post('message_data', '');
        $memberList = $request->post('member', []);
        if ($model->load($request->post('data'), '')) {
            $model->verify_member = $memberList ? array_shift($memberList)['id'] : '';
            $model->user_id = $model->isNewRecord ? $member_id : $model->user_id;
            if ($model->save()) {
                ReportMember::DeleteAll(['pid' => $model->attributes['id']]);
                $ids = ArrayHelper::getColumn($request->post('member', []), 'id', $keepKeys = true);
                ReportMember::addDatas($model->attributes['id'], $ids);
                // 内部消息通知
                $content =  '你有报告待审批' . $model['file_name'];
                Yii::$app->services->workerNotify->createRemind($model['user_id'], $model['id'], SubscriptionReasonEnum::BEHAVIOR_VERIFY, SubscriptionActionEnum::VERIFY_SUCCESS, Yii::$app->user->identity->member_id, $content);
                // 订阅消息
                if ($message_data)
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $model->attributes['id'], MessageActionEnum::VERIFY_SUCCESS, MessageReasonEnum::REPORT_VERIFY);
                // 发送订阅消息，查询审核用户是否订阅消息
                $messageModel = MiniMessage::find()
                    ->where(['member_id' => $model->verify_member, 'is_read' => 0])
                    ->andWhere(['target_id' => MessageActionEnum::VERIFY_CREATE, 'target_type' => MessageReasonEnum::REPORT_VERIFY])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' => $model->file_name ?: '空',
                    ],
                    'thing2' => [
                        'value' => Worker::getRealname(Yii::$app->user->identity->member_id),
                    ],
                    'time3' => [
                        'value' => date('Y-m-d H:i:s'),
                    ],
                ];
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel['id'], $data);
                return true;
            } else {
                throw new NotFoundHttpException($this->getError($model));
            }
        }
        return false;
    }

    /**
     * 提交/撤回 PUT
     * 
     * @param {*returen}
     * @return {*}
     * @throws: 
     */
    public function actionAudit($id)
    {
        $request = Yii::$app->request;
        $audit = $request->post('audit');
        $message_data = $request->post('message_data', '');

        $model = $this->findModel($id);
        // 撤回:0，提交:1  其他错误
        switch ($audit) {
            case 1:
                if ($model->verify != VerifyEnum::OUT && $model->verify != VerifyEnum::SAVE) {
                    throw new NotFoundHttpException('当前状态不支持提交！');
                }
                break;
            case 0:
                if ($model->verify != VerifyEnum::WAIT) {
                    throw new NotFoundHttpException('当前状态不支持撤回！');
                }
                break;
            default:
                throw new NotFoundHttpException('系统繁忙！');
                break;
        }
        $model->verify = $audit ? VerifyEnum::WAIT : VerifyEnum::SAVE;
        // 修改成功，记录审核信息
        if ($model->save()) {
            // 添加日志
            Yii::$app->services->projectReport->addVerifyLog($id, $model->verify);
            // 提交和撤回修改审核人的审核状态’
            if ($audit) {
                // 订阅消息
                if ($message_data)
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $id, MessageActionEnum::VERIFY_SUCCESS, MessageReasonEnum::REPORT_VERIFY);
                ReportMember::updateAll(['verify' => VerifyEnum::WAIT], ['pid' => $id]);
                // 发送订阅消息，查询审核用户是否订阅消息
                $messageModel = MiniMessage::find()
                    ->where(['member_id' => $model->verify_member, 'is_read' => 0])
                    ->andWhere(['action' => MessageActionEnum::VERIFY_CREATE, 'target_type' => MessageReasonEnum::REPORT_VERIFY])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' => $model->file_name ?: '空',
                    ],
                    'thing2' => [
                        'value' => Worker::getRealname(Yii::$app->user->identity->member_id),
                    ],
                    'time3' => [
                        'value' => date('Y-m-d H:i:s'),
                    ],
                ];
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel['id'], $data);
            }
            return true;
        }
        throw new NotFoundHttpException('审批失败！');
    }

    /**
     * 审核 POST
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionVerify($id)
    {
        $request = Yii::$app->request;
        $audit = $request->post('audit');
        $desc = $request->post('desc', NULL);
        $message_data = $request->post('message_data', '');
        $model = $this->findModel($id);
        if ($model->verify != VerifyEnum::WAIT) {
            throw new NotFoundHttpException('报告未提交!');
        }
        try {
            if ($message_data)
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $id, MessageActionEnum::VERIFY_CREATE, MessageReasonEnum::REPORT_VERIFY);
            $reportMemberModel = ReportMember::findOne(['pid' => $id, 'member_id' => $model->verify_member]);
            // 审核通过判断是否有下一位审核人
            // 给下位审核人发送信息或通过审核，给提交人发送信息
            if ($audit) {
                if ($reportMemberModel) {
                    $reportMemberModel->verify = $audit ? VerifyEnum::PASS : VerifyEnum::OUT;
                    $reportMemberModel->save();
                    // 下个审核人
                    $nextModel = ReportMember::find()
                        ->where(['pid' => $id])
                        ->andWhere(['<', 'verify', VerifyEnum::PASS])
                        ->orderBy('id asc')
                        ->asArray()
                        ->one();
                    // 选择下一个审批人

                    $model->verify_member = $nextModel ? $nextModel['member_id'] : '';
                    $model->verify = $nextModel ? $model->verify : VerifyEnum::PASS;

                    // 发送订阅消息，查询审核用户是否订阅消息
                    $messageModel = MiniMessage::find()
                        ->where(['member_id' => $nextModel->member_id, 'is_read' => 0])
                        ->andWhere(['action' => MessageActionEnum::VERIFY_CREATE, 'target_type' => MessageReasonEnum::REPORT_VERIFY])
                        ->andWhere(['status' => StatusEnum::ENABLED])
                        ->orderBy('id desc')
                        ->one();
                    date_default_timezone_set("Asia/Chongqing");
                    $data = [
                        'thing1' => [
                            'value' => $model->file_name ?: '空',
                        ],
                        'thing2' => [
                            'value' => Worker::getRealname(Yii::$app->user->identity->member_id),
                        ],
                        'time3' => [
                            'value' => date('Y-m-d H:i:s'),
                        ],
                    ];
                    if ($messageModel)
                        Yii::$app->services->workerMiniMessage->send($messageModel['id'], $data);
                } else {
                    // 没有下一位
                    $model->verify = VerifyEnum::PASS;

                    // 发送报告审核结果通知
                    $messageModel = MiniMessage::find()
                        ->where(['member_id' => $model->member_id, 'is_read' => 0])
                        ->andWhere(['action' => MessageActionEnum::VERIFY_SUCCESS, 'target_type' => MessageReasonEnum::REPORT_VERIFY])
                        ->andWhere(['status' => StatusEnum::ENABLED])
                        ->orderBy('id desc')
                        ->one();
                    $data = [
                        'thing1' => [
                            'value' =>  $model->type ? ReportEnum::getValue($model->type) : '未定义',
                        ],
                        'phrase2' => [
                            'value' => '通过',
                        ],
                        'time3' => [
                            'value' => date('Y-m-d H:i:s'),
                        ],
                        'thing4' => [
                            'value' => $model->file_name ?: '空'
                        ]
                    ];
                    if ($messageModel)
                        Yii::$app->services->workerMiniMessage->send($messageModel['id'], $data);
                }
            } else {
                $model->verify = VerifyEnum::OUT;
                // 审核失败
                // 发送报告审核结果通知
                $messageModel = MiniMessage::find()
                    ->where(['member_id' => $model->user_id, 'target_id' => $id, 'is_read' => 0])
                    ->andWhere(['action' => MessageActionEnum::VERIFY_SUCCESS, 'target_type' => MessageReasonEnum::REPORT_VERIFY])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' =>  $model->type ? ReportEnum::getValue($model->type) : '未定义',
                    ],
                    'phrase2' => [
                        'value' => '驳回',
                    ],
                    'time3' => [
                        'value' => date('Y-m-d'),
                    ],
                    'thing4' => [
                        'value' => $model->file_name ?: '空'
                    ]
                ];
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel['id'], $data);
            }
            // 添加日志
            Yii::$app->services->projectReport->addVerifyLog($id, $model->verify, $desc);
            return $model->save();
        } catch (NotFoundHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }

        return false;
    }

    public function actionDefault()
    {
        $request = Yii::$app->request;

        $id = $request->get('id', NULL);
        $pid = $request->get('pid', NULL);

        $itemModel = new Report();
        $model = $id ? $this->findModel($id) : $itemModel->loadDefaultValues();

        $map = [];
        foreach (ReportEnum::getMap() as $key => $value) {
            array_push($map, [
                'text' => $value,
                'value' => $key
            ]);
        }
        return [
            'model' => $model,
            'type_map' => ReportEnum::getMap(),
            'type_enum' => $map
        ];
    }
}

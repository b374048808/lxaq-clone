<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-06 09:31:31
 * @Description: 
 */

namespace workapi\modules\v1\controllers\monitor;

use common\enums\company\SubscriptionActionEnum;
use common\enums\company\SubscriptionReasonEnum;
use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\monitor\ItemStepsEnum;
use common\enums\monitor\ItemTypeEnum;
use common\enums\VerifyEnum;
use common\enums\PointEnum;
use workapi\controllers\OnAuthController;
use common\models\monitor\project\Item;
use common\enums\StatusEnum;
use common\models\monitor\project\item\StepsMember;
use yii\web\NotFoundHttpException;
use common\helpers\ArrayHelper;
use common\helpers\MiniHelper;
use common\models\worker\Worker;
use common\models\monitor\project\house\ItemMap;
use common\models\monitor\project\item\StepsLog;
use common\models\worker\MiniMessage;
use yii\web\UnprocessableEntityHttpException;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ItemController extends OnAuthController
{
    public $modelClass = Item::class;


    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['rbac'];


    public function actionIndex($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);      //名称
        $step = $request->get('step', NULL);    //步骤
        $sort = $request->get('sort', NULL);    //排序
        $audit = $request->get('audit', NULL);  //审核状态

        // 排序
        $order = '';
        switch ($sort) {
            case 1:
                $order = 'audit desc';
                break;
            case 2:
                $order = 'start_time desc';
                # code...
                break;
            case 3:
                $order = 'end_time desc';
                # code...
                break;
            case 4:
                $order = 'steps desc';
                # code...
                break;
            default:
                $order = 'id desc';
                break;
        }
        $where = [];
        // 判断是否自己提交，不然只显示能操作的项目
        $model = Item::find()
            ->with(['user'])
            ->select(['id', 'user_id', 'title', 'steps', 'status', 'audit', 'start_time', 'end_time'])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['>', 'audit', VerifyEnum::SAVE])
            ->andFilterWhere(['like', 'title', $title])
            ->andFilterWhere(['audit' => $audit])
            ->andFilterWhere(['steps' => $step])
            ->orderBy($order)
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            $value['steps_text'] = ItemStepsEnum::getValue($value['steps']);    //步骤名称
            $value['start_time'] = date('m-d', $value['start_time']);           //开始时间
            $value['end_time'] = date('m-d', $value['end_time']);               //截止时间
            $value['type_text'] = VerifyEnum::getValue($value['audit']);        //项目状态
        }
        unset($value);

        return $model;
    }

    /**
     * 所有
     * 
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);      //名称
        $step = $request->get('step', NULL);    //步骤
        $sort = $request->get('sort', NULL);    //排序
        $tabbar = $request->get('tabbar', NULL);       //自己提交的
        $audit = $request->get('audit', NULL);  //审核状态

        // 排序
        $order = '';
        switch ($sort) {
            case 1:
                $order = 'audit desc';
                break;
            case 2:
                $order = 'start_time desc';
                # code...
                break;
            case 3:
                $order = 'end_time desc';
                # code...
                break;
            case 4:
                $order = 'steps desc';
                # code...
                break;
            default:
                $order = 'id desc';
                break;
        }
        $where = [];
        // 判断是否自己提交，不然只显示能操作的项目
        switch ($tabbar) {
            case 'wait':
                // 当前用户的权限
                $stepModel = StepsMember::find()
                    ->where(['member_id' => Yii::$app->user->identity->member_id])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->all();
                $stepColumn = ArrayHelper::getColumn($stepModel, 'step_id', $keepKeys = true);
                $where = [
                    'and',
                    ['in', 'steps', $stepColumn],
                    ['audit' => VerifyEnum::PASS],
                    ['<', 'steps', ItemStepsEnum::END]
                ];
                break;
            case 'my':
                $where = ['user_id' => Yii::$app->user->identity->member_id];
                break;
            case 'verify':

                break;
            default:
                # code...
                break;
        }
        // 
        $model = Item::find()
            ->select(['id', 'title', 'steps', 'status', 'audit', 'start_time', 'end_time'])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $title])
            ->andFilterWhere(['audit' => $audit])
            ->andFilterWhere(['steps' => $step])
            ->orderBy($order)
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            $value['steps_text'] = ItemStepsEnum::getValue($value['steps']);    //步骤名称
            $value['start_time'] = date('m-d', $value['start_time']);           //开始时间
            $value['end_time'] = date('m-d', $value['end_time']);               //截止时间
            $value['type_text'] = VerifyEnum::getValue($value['audit']);        //项目状态
        }
        unset($value);

        return $model;
    }

    // 权限判断
    public function actionRbac()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);

        $model = $this->findModel($id);
        $member_id = Yii::$app->user->identity->member_id;
        $contract_rbac = false;
        $collection_rbac = false;
        $steps_rbac = false;

        // 用户步骤权限
        $stepModel = StepsMember::find()
            ->where(['member_id' => Yii::$app->user->identity->member_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $stepColumn = ArrayHelper::getColumn($stepModel, 'step_id', $keepKeys = true);

        // 合同权限
        if (in_array($member_id, Yii::$app->params['adminAccount']) || in_array(ItemStepsEnum::CONTRACT, $stepColumn)) {
            $contract_rbac = true;
            # code...
        }
        // 收款权限
        if (in_array($member_id, Yii::$app->params['adminAccount']) || in_array(ItemStepsEnum::FINANCE, $stepColumn)) {
            $collection_rbac =  true;
            # code...
        }
        // 步骤操作权限
        if (in_array($member_id, Yii::$app->params['adminAccount']) || in_array($model->steps, $stepColumn)) {
            $steps_rbac =  true;
            # code...
        }

        return [
            'contract_rbac' => $contract_rbac,
            'collection_rbac'    => $collection_rbac,
            'steps_rbac'    => $steps_rbac
        ];
    }

    /**
     * 待审核列表
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionWaitList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);      //名称

        $model = Item::find()
            ->with(['user'])
            // ->select(['id', 'title', 'audit', 'status', 'start_time', 'end_time', 'user_id', 'created_at'])
            ->where(['audit' => VerifyEnum::WAIT])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['title' => $title])
            ->offset($start)
            ->limit($limit)
            ->orderBy('id desc')
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            $value['user'] = $value['user']['realname'];
            $value['audit_text'] = VerifyEnum::getValue($value['audit']);
            $value['type_text'] = VerifyEnum::getValue($value['audit']);
        }
        unset($value);

        return $model;
    }

    /**
     * 项目详情
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionView($id)
    {
        // 关联审核记录，合同，最新审核记录，其他配置
        $model = Item::find()
            ->with(['auditLog', 'contract', 'newVerifyLog', 'config'])
            ->where(['id' => $id])
            ->asArray()
            ->one();

        // 转换其他配置信息为数组
        $list = [];
        // 类型数据转换,判断数据类型是否为数组
        if ($model['config']) {
            $type = json_decode($model['config']['type'], true);
            if (is_array($type)) {
                foreach ($type as $key => $value) {
                    array_push($list, PointEnum::getValue($value));
                    # code...
                }
            }
            $model['config']['type'] = $list;
        }
        // 遍历记录中的时间
        foreach ($model['auditLog'] as $key => &$value) {
            $value['time'] = date('Y-m-d H:i:s', $value['created_at']);
            # code...
        }
        unset($value);
        // 转化关联合同的格式
        foreach ($model['contract'] as $key => &$value) {
            $value['file'] = $value['file'] ? json_decode($value['file'], true) : [];
            $value['event_time']  = date('Y-m-d', $value['event_time']);
            $value['manager'] = $value['manager'] > 0 ? Worker::getRealname($value['manager']) : '';
        }
        unset($value);
        // 转化格式
        // StringHelper::getThumbUrl($url, $width, $height)缩略图
        $model['detail_address'] = Yii::$app->services->provinces->getCityListName([$model['province_id'], $model['city_id'], $model['area_id']]) . $model['address'];
        $model['type'] = ItemTypeEnum::getValue($model['type']);
        $model['images'] = $model['images'] ? json_decode($model['images'], true) : [];
        $model['collection'] = json_decode($model['collection'], true);
        $model['type_text'] = ItemTypeEnum::getValue($model['type']);
        $model['audit_text'] = VerifyEnum::getValue($model['audit']);
        $model['start_time'] = $model['start_time'] ? date('Y-m-d', $model['start_time']) : '';
        $model['end_time'] = $model['end_time'] ? date('Y-m-d', $model['end_time']) : '';
        $model['file'] = $model['file'] ? json_decode($model['file'], true) : '';
        $model['role'] = StepsMember::getRole(Yii::$app->user->identity->member_id, $model['steps']);
        $model['map_list'] = ItemMap::getHouseIds($id);


        return $model;
    }

    /**
     * 编辑
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $ids = $request->post('ids', []);
        $message_data = $request->post('message_data', '');

        $model = $id ? $this->findModel($id) : new Item();
        $member_id = Yii::$app->user->identity->member_id;
        $item = $request->post('data');
        if ($model->load($item, '')) {
            $model->user_id = $member_id;
            // 失败返回错误信息，成功
            if (!$model->save()) {
                throw new NotFoundHttpException($this->getError($model));
            } else {
                // 后订阅项目审核的消息
                if ($message_data)
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $model->attributes['id'], MessageActionEnum::VERIFY_SUCCESS, MessageReasonEnum::ITEM_VERIFY);
                // 删除关联，添加提交的关系
                ItemMap::deleteAll(['item_id' => $id]);
                ItemMap::addHouses($model->attributes['id'], $ids);

                // 微信通知
                if ($model->audit == VerifyEnum::WAIT) {
                    $messageModel = MiniMessage::find()
                        ->where([
                            'is_read' => 0,
                            'action' => MessageActionEnum::VERIFY_WAIT,
                            'target_type' => MessageReasonEnum::ITEM_VERIFY
                        ])
                        ->asArray()
                        ->all();
                    $data = [
                        'thing1' => [
                            'value' => mb_substr($model->title, 0, 15),
                        ],
                        'thing4' => [
                            'value' => Worker::getRealname($model->user_id),
                        ],
                        'time5' => [
                            'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : '') . '~' . ($model->end_time ? date('Y-m-d', $model->end_time) : ''),
                        ],
                        'thing2' => [
                            'value' =>  ItemTypeEnum::getValue($model->type)
                        ],
                    ];
                    foreach ($messageModel ?: [] as $value) {
                        return Yii::$app->services->workerMiniMessage->send($value['id'], $data, 'pages/monitor-item-view/monitor-item-view?itemId=' . $model->attributes['id']);
                    }
                }



                return $model->attributes['id'];
            }
        }
        // 如过没有对应上模型则返回
        throw new NotFoundHttpException($this->getError($model));
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
        $audit = $request->post('audit');       //审核状态
        $desc = $request->post('description', NULL);   //审核备注
        $message_data = $request->post('message_data', '');

        $model = $this->findModel($id);
        // 项目状态为提交时才能审核
        if ($model->audit != VerifyEnum::WAIT) {
            throw new NotFoundHttpException('项目未提交!');
        }
        $model->audit = $audit ? VerifyEnum::PASS : VerifyEnum::OUT;
        // 修改成功，记录审核信息
        if ($model->save()) {
            // 订阅下次项目提交的订阅消息
            if ($message_data)
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::VERIFY_WAIT, MessageReasonEnum::ITEM_VERIFY);
            $realname = Worker::getRealname(Yii::$app->user->identity->member_id);
            // 内部消息通知
            $content = '你提交的项目' . $model['title'] . '审批:' . ($audit ? '通过' : '驳回') . '。审批人:' . $realname . '。备注:' . $desc;
            Yii::$app->services->workerNotify->createRemind($model['user_id'], $model['id'], SubscriptionReasonEnum::BEHAVIOR_VERIFY, SubscriptionActionEnum::VERIFY_SUCCESS, Yii::$app->user->identity->member_id, $content);
            // 审核通过通知步骤人
            if ($audit) {
                $stepModel = StepsMember::find()
                    ->where(['step_id' => $model['steps']])
                    // ->andWhere(['!=', 'member_id', Yii::$app->user->identity->member_id])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->all();
                $stepColumn = ArrayHelper::getColumn($stepModel, 'member_id', $keepKeys = true);
                // 内部通知
                foreach ($stepColumn as $key => $value) {
                    // ding
                    $content = '项目' . $model['title'] . '已进行到步骤:' . ItemStepsEnum::getValue($model->steps) . '。备注:' . $desc;
                    Yii::$app->services->workerNotify->createRemind($value, $model['id'], SubscriptionReasonEnum::BEHAVIOR_VERIFY, SubscriptionActionEnum::VERIFY_SUCCESS, Yii::$app->user->identity->member_id, $content);
                }
                $workerModel = Worker::find()
                    ->where(['in', 'id', $stepColumn])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->all();
                $memberIds = ArrayHelper::getColumn($workerModel, 'mobile', $keepKeys = true);
                $realname = Worker::getRealname(Yii::$app->user->identity->member_id);
                // 通知添加项目编号
                MiniHelper::getItemNumber($model->title, Worker::getRealname($model->user_id));
                // 短信通知
                foreach ($memberIds as $key => $value) {
                    // Yii::$app->services->workerVerifySms->send($value, $realname . ($audit ? '通过' : '驳回') . '下步步骤' . ItemStepsEnum::getValue($model['steps']), '项目名:' . $model['title'] . '审核意见:' . $desc, 'manager-verify');
                }
            }

            // 添加日志
            Yii::$app->services->monitorItem->addVerifyLog($id, $model->audit, $desc);

            // 微信通知
            $messageModel = MiniMessage::findOne([
                'is_read' => 0,
                'action' => MessageActionEnum::VERIFY_SUCCESS,
                'target_type' => MessageReasonEnum::ITEM_VERIFY,
                'target_id' => $model->id
            ]);
            $data = [
                'thing6' => [
                    'value' => mb_substr($model->title, 0, 15),
                ],
                'name3' => [
                    'value' => $realname,
                ],
                'thing5' => [
                    'value' => $desc ?: '空',
                ],
                'phrase7' => [
                    'value' =>  $audit ? '通过' : '驳回'
                ],
            ];
            // 有订阅
            if ($messageModel)
                Yii::$app->services->workerMiniMessage->send($messageModel->id, $data, 'pages/monitor-item-view/monitor-item-view?itemId=' . $id);
            return true;
        }
        throw new NotFoundHttpException('审批失败！');
    }

    /**
     * 步骤确认
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionSteps($id)
    {
        $request = Yii::$app->request;

        $audit = $request->post('audit', true);
        $desc = $request->post('description', '');
        $message_data = $request->post('message_data', '');

        $model = Item::findOne($id);
        // 项目步骤根据审核状态进行变更
        try {
            Item::updateAllCounters(['steps' => $audit ? 1 : -1], ['id' => $id]);
            // 订阅下次项目提交的订阅消息
            if ($message_data) {
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', ItemStepsEnum::getAction($model->steps), MessageReasonEnum::ITEM_STEPS);
                //步骤驳回通知
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $model->id, MessageActionEnum::STEPS_OUT, MessageReasonEnum::ITEM_STEPS);
            }


            $this->getStepNextSubMessage($model, $audit ? $model->steps + 1 : $model->steps - 1);
            if ($audit) {
                $stepModel = StepsMember::find()
                    ->where(['step_id' => $audit ? $model->steps + 1 : $model->steps - 1])
                    ->andWhere(['!=', 'member_id', Yii::$app->user->identity->member_id])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->all();
                $stepColumn = ArrayHelper::getColumn($stepModel, 'member_id', $keepKeys = true);
                $workerModel = Worker::find()
                    ->where(['in', 'id', $stepColumn])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->all();
                $memberIds = ArrayHelper::getColumn($workerModel, 'mobile', $keepKeys = true);

                $realname = Worker::getRealname(Yii::$app->user->identity->member_id);
                // 内部通知
                $content = '你提交的项目' . $model['title'] . '已进行到步骤:' . ItemStepsEnum::getValue($audit ? $model->steps + 1 : $model->steps - 1) . '。备注:' . $desc;
                Yii::$app->services->workerNotify->createRemind($model['user_id'], $model['id'], SubscriptionReasonEnum::BEHAVIOR_VERIFY, SubscriptionActionEnum::VERIFY_SUCCESS, Yii::$app->user->identity->member_id, $content);
                // 短信通知
                foreach ($memberIds as $key => $value) {
                    // Yii::$app->services->workerVerifySms->send($value, $realname . ($audit ? '通过' : '驳回'), '项目名:' . $model['title'] . '审核意见:' . $desc, 'manager-verify');
                }
                // 添加日志
                Yii::$app->services->monitorItem->addVerifyLog($id, $model->audit, $desc);
            } else {
                $stepLogModel = StepsLog::find()
                    ->where(['pid' => $id])
                    ->andWhere(['verify' => 1])
                    ->orderBy('id desc')
                    ->asArray()
                    ->one();
                if ($stepLogModel) {
                    $workerModel = Worker::find()
                        ->where(['id' => $stepLogModel['member_id']])
                        ->asArray()
                        ->one();

                    $realname = Worker::getRealname(Yii::$app->user->identity->member_id);
                    // 内部通知
                    $content = '你提交的项目' . $model['title'] . '步骤' . ItemStepsEnum::getValue($model['steps']) . '由' . $realname . '驳回。备注:' . $desc;
                    Yii::$app->services->workerNotify->createRemind($workerModel['id'], $model['id'], SubscriptionReasonEnum::BEHAVIOR_VERIFY, SubscriptionActionEnum::VERIFY_OUT, Yii::$app->user->identity->member_id, $content);
                    // if ($workerModel['mobile'])
                    // Yii::$app->services->workerVerifySms->send($workerModel['mobile'], $realname . ($audit ? '通过' : '驳回'), '项目名:' . $model['title'] . '审核意见:' . $desc, 'manager-verify');
                    // 微信通知
                    $messageModel = MiniMessage::findOne([
                        'is_read' => 0,
                        'action' => MessageActionEnum::STEPS_OUT,
                        'target_type' => MessageReasonEnum::ITEM_STEPS,
                        'target_id' => $model->id,
                        'member_id' => $stepLogModel['member_id']
                    ]);
                    $data = [
                        'thing1' => [
                            'value' => mb_substr($model->title, 0, 15) . '[' . ItemStepsEnum::getValue($model->steps) . ']',
                        ],
                        'thing3' => [
                            'value' => $desc ?: '空',
                        ],
                    ];
                    if ($message_data)
                        Yii::$app->services->workerMiniMessage->send($messageModel->id, $data, 'pages/monitor-item-view/monitor-item-view?itemId=' . $id);
                }
            }
            Yii::$app->services->monitorItem->addStepsLog($id, $audit, $desc);
            return true;
        } catch (NotFoundHttpException $e) {
            throw new UnprocessableEntityHttpException($e->getMessage());
        } catch (\Exception $e) {
            throw new NotFoundHttpException($e->getMessage());
        }
    }

    private function getStepNextSubMessage($model, $steps)
    {
        $notifyType = '';
        $member_ids = StepsMember::getMemberColumn($steps);
        switch ($steps) {
            case ItemStepsEnum::CONTRACT:
                $notifyType = MessageActionEnum::STEPS_CONTART;

                $data = [
                    'character_string1' => [
                        'value' => mb_substr($model->title, 0, 15),
                    ],
                    'amount4' => [
                        'value' => $model->money ?: '空',
                    ],
                    'thing3' => [
                        'value' => $model->belonger ?: '空',
                    ],
                ];
                # code...
                break;
            case ItemStepsEnum::FOR:
                $notifyType = MessageActionEnum::STEPS_SERVICE;
                $data = [
                    'thing1' => [
                        'value' => mb_substr($model->title, 0, 15),
                    ],
                    'time4' => [
                        'value' => $model->end_time ? date('Y-m-d', $model->end_time) : '空',
                    ],
                    'time5' => [
                        'value' => $model->end_time ? date('n', time() - $model->end_time) . '天' : '0天',
                    ],
                ];
                # code...
                break;
            case ItemStepsEnum::FINANCE:
                $notifyType = MessageActionEnum::STEPS_MONEY;
                $data = [
                    'thing1' => [
                        'value' => mb_substr($model->title, 0, 15),
                    ],
                    'time2' => [
                        'value' => $model->end_time ? date('Y-m-d', $model->end_time) : '空',
                    ],
                    'amount3' => [
                        'value' => $model->money ?: '空',
                    ],
                ];
                # code...
                break;
            case ItemStepsEnum::END:
                $notifyType = MessageActionEnum::STEPS_END;
                $data = [
                    'character_string1' => [
                        'value' => $model->number ?: '空',
                    ],
                    'thing2' => [
                        'value' => $model->title ?: '空',
                    ],
                    'thing3' => [
                        'value' => ItemStepsEnum::getValue(ItemStepsEnum::END)
                    ],
                    'amount3' => [
                        'value' => $model->end_time ? date('Y-m-d', $model->end_time) : '空',
                    ],
                ];
                # code...
                break;
        }

        // 微信通知
        $messageModel = MiniMessage::find()
            ->where([
                'is_read' => 0,
                'action' => $notifyType,
                'target_type' => MessageReasonEnum::ITEM_STEPS
            ])
            ->andWhere(['in', 'member_id', $member_ids])
            ->asArray()
            ->all();
        // 有订阅
        foreach ($messageModel ?: [] as  $value) {
            Yii::$app->services->workerMiniMessage->send($value['id'], $data);
        }
    }

    /**
     * 撤回/提交
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionAudit($id)
    {
        $request = Yii::$app->request;
        $audit = $request->post('audit');
        $message_data = $request->post('message_data', NULL);
        $member_id = Yii::$app->user->identity->member_id;

        $model = $this->findModel($id);
        if ($member_id != $model['user_id']) {
            throw new NotFoundHttpException('无权操作！');
            # code...
        }
        // 撤回:0，提交:1  其他错误
        switch ($audit) {
            case 1:
                if ($model->audit != VerifyEnum::OUT && $model->audit != VerifyEnum::SAVE) {
                    throw new NotFoundHttpException('当前状态不支持提交！');
                }
                break;
            case 0:
                if ($model->audit != VerifyEnum::WAIT) {
                    throw new NotFoundHttpException('当前状态不支持撤回！');
                }
                break;
            default:
                throw new NotFoundHttpException('系统繁忙！');
                break;
        }
        $realname = Worker::getRealname(Yii::$app->user->identity->member_id);
        $model->audit = $audit ? VerifyEnum::WAIT : VerifyEnum::SAVE;
        if ($model->save()) {
            if ($audit) {
                if ($message_data)
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $model->attributes['id'], MessageActionEnum::VERIFY_SUCCESS, MessageReasonEnum::ITEM_VERIFY);
                // 提交给订阅发送消息
                $messageModel = MiniMessage::find()
                    ->where([
                        'is_read' => 0,
                        'action' => MessageActionEnum::VERIFY_WAIT,
                        'target_type' => MessageReasonEnum::ITEM_VERIFY,
                    ])
                    ->asArray()
                    ->all();
                $data = [
                    'thing1' => [
                        'value' =>  $model->title,
                    ],
                    'thing4' => [
                        'value' => $realname,
                    ],
                    'time5' => [
                        'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : '') . '~' . ($model->end_time ? date('Y-m-d', $model->end_time) : ''),
                    ],
                    'thing2' => [
                        'value' => ItemTypeEnum::getValue($model->type)
                    ],
                ];
                // 发送订阅消息
                if ($messageModel) {
                    foreach ($messageModel as $key => $value) {
                        Yii::$app->services->workerMiniMessage->send($value->id, $data, 'pages/monitor-view/monitor-item-view?itemId=' . $id);
                    }
                }
            }

            // Yii::$app->services->workerVerifySms->send('13706889990', $realname, '项目名:' . $model['title'], 'manager-verify');
            // 添加日志
            Yii::$app->services->monitorItem->addVerifyLog($id, $model->audit);
            # code...
        }
        return $model;
    }


    /**
     * 编辑默认配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);

        $itemModel = new Item();
        $model = $id ? $this->findModel($id) : $itemModel->loadDefaultValues();

        return [
            'model' => $model,
            'typeEnum'  => PointEnum::getMap(),
            'cate_enum'  => ItemTypeEnum::getMap(),
            'mapList' => $id ? ItemMap::getHouseIds($id) : [],
        ];
    }
}

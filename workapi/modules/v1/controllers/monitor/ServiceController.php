<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-14 17:55:44
 * @Description: 
 */

namespace workapi\modules\v1\controllers\monitor;

use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\StatusEnum;
use common\models\monitor\project\service\Service;
use workapi\controllers\OnAuthController;
use common\enums\monitor\ItemTypeEnum;
use common\enums\VerifyEnum;
use common\helpers\ArrayHelper;
use yii\web\NotFoundHttpException;
use common\models\monitor\project\house\ItemMap;
use common\models\monitor\project\house\Report;
use common\models\monitor\project\Item;
use common\models\monitor\project\service\Map;
use common\models\worker\MiniMessage;
use common\models\worker\Worker;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceController extends OnAuthController
{
    public $modelClass = Service::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['rbac', 'index'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $audit = $request->get('audit', NULL);  //状态
        $sort = $request->get('sort', NULL);
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
            default:
                $order = 'id desc';
                break;
        }

        $model = Service::find()
            ->with(['item', 'user' => function ($queue) {
                $queue->select(['id', 'realname']);
            }, 'member' => function ($queue) {
                $queue->select(['id', 'realname']);
            }])
            ->andWhere(['>', 'audit', VerifyEnum::SAVE])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['audit' => $audit])
            ->orderBy($order)
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            $value['audit_text'] =  VerifyEnum::getValue($value['audit']);
            $value['cate_text'] = $value['cate']['title'];
        }
        unset($value);

        return $model;
    }

    /**
     * 任务列表
     * 
     * @param {*} $start
     * @param {*} $limit
     * @return {*}
     * @throws: 
     */
    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $audit = $request->get('audit', NULL);  //状态
        $sort = $request->get('sort', NULL);
        $pid = $request->get('pid', NULL);
        $tabbar = $request->get('tabbar', 'all');
        $content = $request->get('title', NULL);

        $where = [];
        switch ($tabbar) {
            case 'my':
                $where =  ['publisher' => Yii::$app->user->identity->member_id];
                # code...
                break;
            case 'wait':
                $where = [
                    'and',
                    ['manager' => Yii::$app->user->identity->member_id],
                    ['<', 'audit', VerifyEnum::WAIT]
                ];
                break;
            case 'verify':
                if (in_array(Yii::$app->user->identity->member_id, Yii::$app->params['adminAccount'])) {
                    $where = ['audit' => VerifyEnum::WAIT];
                } else {
                    $where = ['and', ['audit' => VerifyEnum::WAIT], ['publisher' => Yii::$app->user->identity->member_id]];
                }

                break;
            default:
                # code...
                break;
        }
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
            default:
                $order = 'id desc';
                break;
        }
        $itemWhere = [];
        if ($content) {
            $itemModel = Item::find()
                ->where(['like', 'title', $content])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->asArray()
                ->all();
            $itemIds = ArrayHelper::getColumn($itemModel, 'id', $keepKeys = true);
            $itemWhere = ['in', 'pid', $itemIds];
        }
        $model = Service::find()
            ->with(['item', 'user' => function ($queue) {
                $queue->select(['id', 'realname']);
            }, 'member' => function ($queue) {
                $queue->select(['id', 'realname']);
            }])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['audit' => $audit])
            ->andWhere($itemWhere)
            ->andFilterWhere(['pid' => $pid])
            ->orderBy($order)
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            $value['audit_text'] =  VerifyEnum::getValue($value['audit']);
            $value['cate_text'] = $value['cate']['title'];
        }
        unset($value);

        return $model;
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
        $desc = $request->post('desc', NULL);   //审核备注
        $message_data = $request->post('message_data', NULL);
        $member_id = Yii::$app->user->identity->member_id;

        $model = $this->findModel($id);
        // 项目状态为提交时才能审核
        if ($model->audit != VerifyEnum::WAIT) {
            throw new NotFoundHttpException('任务未提交!');
        }
        $reportIds = Map::getReportIds($id);
        if (Report::find()->where(['in', 'id', $reportIds])->andWhere(['<', 'verify', VerifyEnum::PASS])->andWhere(['status' => StatusEnum::ENABLED])->exists()) {
            throw new NotFoundHttpException('有报告未审核!');
            # code...
        }
        $model->audit = $audit ? VerifyEnum::PASS : VerifyEnum::OUT;
        // 修改成功，记录审核信息
        if ($model->save()) {
            // 提交给提交人发送消息
            $messageModel = MiniMessage::findOne([
                'is_read' => 0,
                'action' => MessageActionEnum::VERIFY_SUCCESS,
                'target_type' => MessageReasonEnum::SERVICE_VERIFY,
                'target_id' => $model->id
            ]);
            $data = [
                'thing6' => [
                    'value' => '[任务]' . mb_substr(($model->item->title ?: '未关联项目'), 0, 15),
                ],
                'phrase7' => [
                    'value' => $audit ? '通过' : '驳回',
                ],
                'name3' => [
                    'value' => Worker::getRealname($member_id),
                ],
                'thing5' => [
                    'value' => $desc ?: "空"
                ],
            ];
            // 有订阅
            if ($messageModel)
                Yii::$app->services->workerMiniMessage->send($messageModel->id, $data, 'pages/service-view/service-view?itemId=' . $id);
            // 提交订阅审核通知(审核结果，任务审核)
            if ($message_data)
                Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::VERIFY_WAIT, MessageReasonEnum::SERVICE_VERIFY);
            // 添加日志
            Yii::$app->services->monitorService->addVerifyLog($id, $model->audit, $desc);
            return true;
        }
        throw new NotFoundHttpException('审批失败！');
    }

    /**
     * 撤回/提交 PUT
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionAudit($id)
    {
        $request = Yii::$app->request;
        $audit = $request->post('audit');
        $member_id = Yii::$app->user->identity->member_id;
        $message_data = $request->post('message_data', NULL);

        $model = $this->findModel($id);
        if ($member_id != $model['manager']) {
            throw new NotFoundHttpException('无权操作！');
            # code...
        }
        // 撤回:0，提交:1  其他错误
        switch ($audit) {
            case 1:
                if ($model->audit != VerifyEnum::OUT && $model->audit != VerifyEnum::SAVE) {
                    throw new NotFoundHttpException('当前状态不支持提交！');
                }
                $reportIds = Map::getReportIds($id);
                if (Report::find()->where(['in', 'id', $reportIds])->andWhere(['<', 'verify', VerifyEnum::PASS])->andWhere(['status' => StatusEnum::ENABLED])->exists()) {
                    throw new NotFoundHttpException('有报告未审核!');
                    # code...
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
        $model->audit = $audit ? VerifyEnum::WAIT : VerifyEnum::SAVE;
        if ($model->save()) {
            // 提交时发送订阅消息
            if ($audit) {
                // 提交给创建人发送消息
                $messageModel = MiniMessage::find()
                    ->where([
                        'member_id' => $model->publisher,
                        'is_read' => 0,
                        'action' => MessageActionEnum::VERIFY_WAIT,
                        'target_type' => MessageReasonEnum::SERVICE_VERIFY,
                    ])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' => '[任务]' . mb_substr(($model->item->title ?: '未关联项目'), 0, 15),
                    ],
                    'thing2' => [
                        'value' => $model->description ? mb_substr($model->description, 0, 20) : '空',
                    ],
                    'name3' => [
                        'value' => Worker::getRealname($member_id),
                    ],
                    'thing6' => [
                        'value' => '任务已完成，请及时审核！'
                    ],
                ];
                // 发送消息
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel->id, $data, 'pages/service-view/service-view?itemId=' . $id);

                // 提交订阅审核通知(审核结果，任务审核)
                if ($message_data) {
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $model->attributes['id'], MessageActionEnum::VERIFY_SUCCESS, MessageReasonEnum::SERVICE_VERIFY);
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::VERIFY_CREATE, MessageReasonEnum::SERVICE_VERIFY);
                }
            }

            // 添加日志
            Yii::$app->services->monitorService->addVerifyLog($id, $model->audit);
            # code...
            return true;
        }
        return $model;
    }

    // 订阅消息
    public function actionOnshubMessage()
    {
        $request = Yii::$app->request;
        $message = $request->post('message');
        return Yii::$app->services->workerMiniMessage->createRemind($message['openid'], '', MessageActionEnum::REMIND, MessageReasonEnum::SERVICE_VERIFY);
    }

    /**
     * 详情
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionView($id)
    {
        $model = Service::find()
            ->with(['user', 'map', 'item', 'newVerifyLog', 'newAuditLog', 'member' => function ($queue) {
                $queue->select(['id', 'realname']);
            }])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $model['start_time'] = date('Y-m-d', $model['start_time']);
        $model['end_time'] = date('Y-m-d', $model['end_time']);
        $model['manager_name'] = $model['user']['realname'] ?: '管理员';
        if ($model['item']) {
            $model['item']['type'] = ItemTypeEnum::getValue($model['item']['type']);
            $model['item']['images'] = json_decode($model['item']['images'], true);
            $model['item']['file'] = json_decode($model['item']['file'], true);
            $model['item']['detail_address'] = Yii::$app->services->provinces->getCityListName([$model['item']['province_id'], $model['item']['city_id'], $model['item']['area_id']]) . $model['item']['address'];
        }


        return $model;
    }

    /**
     * 创建任务
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
        $item = $request->post('data');
        $message_data = $request->post('message_data', '');

        $model = $id ? $this->findModel($id) : new Service();
        if ($model->load($item, '')) {
            $model->publisher = $model->isNewRecord ? Yii::$app->user->identity->member_id : $model->publisher;
            // 
            if ($model->save()) {
                // 创建后订阅项目审核的消息
                if ($message_data)
                    Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], $model->attributes['id'], MessageActionEnum::VERIFY_WAIT, MessageReasonEnum::SERVICE_VERIFY);
                if (!$id) {
                    Map::addHouses($model->attributes['id'], $ids);
                } else {
                    Map::deleteAll(['and', ['pid' => $id], ['not in', 'map_id', $ids]]);
                    $mapList = Map::getHouseIds($id);
                    $diff = array_diff($ids, $mapList);
                    Map::addHouses($id, $diff);
                }
                // 发送订阅消息，查询是否有人订阅任务创建消息
                $messageModel = MiniMessage::find()
                    ->where(['member_id' => $model->manager, 'is_read' => 0])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['target_type' => MessageReasonEnum::SERVICE_VERIFY, 'target_id' => MessageActionEnum::VERIFY_CREATE])
                    ->orderBy('id desc')
                    ->one();
                $data = [
                    'thing1' => [
                        'value' => mb_substr(($model->item->title ?: '未关联项目'), 0, 15),
                    ],
                    'thing2' => [
                        'value' => mb_substr($model->description, 0, 20)
                    ],
                    'time3' => [
                        'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : date('Y-m-d')),
                    ],
                    'name4' => [
                        'value' => $model->publisher ? Worker::getRealname($model->publisher) : '管理员'
                    ],
                    'thing14' => [
                        'value' => ItemTypeEnum::getValue($model->item->type),
                    ],
                ];
                if ($messageModel)
                    Yii::$app->services->workerMiniMessage->send($messageModel->id, $data, 'pages/service-view/service-view?itemId=' . $id);

                return true;
            }
        }

        throw new NotFoundHttpException($this->getError($model));
    }

    // 权限判断
    public function actionRbac()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $model = $this->findModel($id);

        $verifyRbac = false;
        $auditRbac = false;
        $member_id = Yii::$app->user->identity->member_id;
        if (in_array($member_id, Yii::$app->params['adminAccount']) || $member_id == $model->publisher) {

            $verifyRbac =  $model->audit == VerifyEnum::WAIT;
            # code...
        }
        if ($member_id == $model->manager) {
            $auditRbac = true;
        }
        return [
            'verify_rbac' => $verifyRbac,
            'audit_rbac'    => $auditRbac,
        ];
    }

    /**
     * 创建关联子任务
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionHouseMap($id)
    {
        $request = Yii::$app->request;
        $result = $request->post('result', []);

        return Map::addHouses($id, $result);
    }

    /**
     * 任务默认配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $pid = $request->get('pid', NULL);

        $itemModel = new Service();
        $model = $itemModel->loadDefaultValues();
        if ($id) {
            $model = Service::find()
                ->with('item')
                ->where(['id' => $id])
                ->asArray()
                ->one();
        }
        $itemMapModel = [];
        if ($id) {
            $itemMapModel = Map::find()
                ->with(['house' => function ($queue) {
                    $queue->select(['id', 'title']);
                }])
                ->where(['pid' => $id])
                ->asArray()
                ->all();
        } elseif ($pid) {
            $itemMapModel = ItemMap::find()
                ->with(['house' => function ($queue) {
                    $queue->select(['id', 'title']);
                }])
                ->where(['item_id' => $pid])
                ->asArray()
                ->all();
        }

        return [
            'model' => $model,
            'items' => Item::getDropDown(),
            'itemMapModel' => $itemMapModel,
            'mapList' => $id ? Map::getHouseIds($id) : ItemMap::getHouseIds($pid),
        ];
    }
}

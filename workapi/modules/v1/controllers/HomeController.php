<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-06 08:45:51
 * @Description: 
 */

namespace workapi\modules\v1\controllers;

use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\helpers\ArrayHelper;
use workapi\controllers\OnAuthController;
use Yii;
use common\models\monitor\project\house\Report;
use common\models\monitor\project\Item;
use common\models\monitor\project\item\StepsMember;
use common\models\monitor\project\service\Service as ServiceService;
use common\models\worker\Notify;
use common\models\worker\NotifyMember;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package workapi\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class HomeController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index',];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $member_id = Yii::$app->user->identity->member_id;
        $reportModel = Report::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['verify_member' => $member_id])
            ->andWHere(['verify' => VerifyEnum::WAIT])
            ->exists();
        // 测试创建通知
        // Yii::$app->services->workerNotify->createRemind(1,SubscriptionReasonEnum::BEHAVIOR_CREATE,SubscriptionActionEnum::BEHAVIOR_INFO,$member_id,'测试');
        Yii::$app->services->workerNotify->pullAnnounce($member_id, Yii::$app->user->identity->created_at);
        // 拉取订阅
        // Yii::$app->services->workerNotify->pullRemind();

        $adminIds = Yii::$app->params['adminAccount'];


        $announce = NotifyMember::find()
            ->with('notify')
            ->where(['status' => StatusEnum::ENABLED, 'is_read' => 0])
            ->andWhere(['type' => Notify::TYPE_ANNOUNCE])
            ->andWhere(['member_id' => $member_id])
            ->orderBy('id desc')
            ->asArray()
            ->one();
        $announceList = NotifyMember::find()
            ->with('notify')
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['type' => Notify::TYPE_ANNOUNCE])
            ->andWhere(['member_id' => $member_id])
            ->orderBy('id desc')
            ->asArray()
            ->all();
        $remindCount = NotifyMember::find()
            ->where(['status' => StatusEnum::ENABLED, 'is_read' => 0])
            ->andWhere(['type' => Notify::TYPE_REMIND])
            ->andWhere(['member_id' => $member_id])
            ->count();
        $messageCount = NotifyMember::find()
            ->where(['status' => StatusEnum::ENABLED, 'is_read' => 0])
            ->andWhere(['type' => Notify::TYPE_MESSAGE])
            ->andWhere(['member_id' => $member_id])
            ->count();
        $itemExists = false;
        $serviceExists = false;
        // 待处理项目
        $stepModel = StepsMember::find()
            ->where(['member_id' => Yii::$app->user->identity->member_id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $stepColumn = ArrayHelper::getColumn($stepModel, 'step_id', $keepKeys = true);
        $where = [
            'and',
            ['in', 'steps', $stepColumn],
            ['audit' => VerifyEnum::PASS]
        ];
        $itemStetps = Item::find()
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->exists();
        // 待处理任务
        $waitService = ServiceService::find()
            ->where(['manager' => $member_id])
            ->andWhere(['audit' => VerifyEnum::SAVE])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->exists();
        if (in_array($member_id, $adminIds)) {
            $itemExists = Item::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['audit' => VerifyEnum::WAIT])
                ->exists();
            $serviceExists = ServiceService::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['audit' => VerifyEnum::WAIT])
                ->exists();
        }
        // 编号管理
        $numberModel = Item::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['audit' => VerifyEnum::PASS])
            ->andWhere(['number' => ''])
            ->count();


        return [
            'wait_report_count' =>  $reportModel,
            'announce' => $announce,
            'remind_count'   => $remindCount,
            'announce_list' => $announceList,
            'message_count'  => $messageCount,
            'itemExists' => $itemExists,
            'serviceExists' => $serviceExists,
            'itemStetps'    => $itemStetps,
            'waitService'   => $waitService,
            'item_number_count' => $numberModel
        ];
    }
}

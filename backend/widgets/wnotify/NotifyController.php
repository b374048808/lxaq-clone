<?php

namespace backend\widgets\wnotify;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\worker\NotifyMember;
use backend\controllers\BaseController;
use common\models\worker\Notify;

/**
 * Class NotifyController
 * @package common\widgets\notify
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyController extends BaseController
{
    protected $view = '@backend/widgets/wnotify/views/';

    /**
     * 公告
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionAnnounce($id)
    {
        $searchModel = new SearchModel([
            'model' => NotifyMember::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['member_id' => $id])
            ->andWhere(['type' => Notify::TYPE_ANNOUNCE])
            ->with(['notifySenderForMember']);

        return $this->render($this->view . $this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'id' => $id
        ]);
    }

    /**
     * 公告详情
     *
     * @param $id
     * @return mixed|string
     */
    public function actionAnnounceView($id)
    {
        if (empty($id) || empty(($model = NotifyMember::find()->where([
                'id' => $id,
                'status' => StatusEnum::ENABLED
            ])->one()))) {
            return $this->message('找不到该公告', $this->redirect(['index']), 'error');
        }

        // 设置公告为已读
        Yii::$app->services->wrokerNotify->read(Yii::$app->user->id, [$model->notify_id]);

        return $this->render($this->view . $this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 私信
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionMessage($id)
    {
        $searchModel = new SearchModel([
            'model' => NotifyMember::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['content'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->with(['notifySenderForMember'])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => Notify::TYPE_MESSAGE, 'member_id' => $id])
            ->with('notify');

        if ($data = $dataProvider->getModels()) {
            $ids = [];
            foreach ($data as $datum) {
                $datum['is_read'] == 0 && $ids[] = $datum->notify_id;
            }

        }

        return $this->render($this->view . $this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'id' => $id
        ]);
    }

    /**
     * 提醒
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRemind($id)
    {
        $searchModel = new SearchModel([
            'model' => NotifyMember::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['content'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->with(['notifySenderForMember', 'notify'])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => Notify::TYPE_REMIND, 'member_id' => $id]);

        if ($data = $dataProvider->getModels()) {
            $ids = [];
            foreach ($data as $datum) {
                $datum['is_read'] == 0 && $ids[] = $datum->notify_id;
            }

            // 设置消息为已读
            !empty($ids) && Yii::$app->services->wrokerNotify->read(Yii::$app->user->id, $ids);
        }

        return $this->render($this->view . $this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'id' => $id
        ]);
    }

    /**
     * @return mixed
     */
    public function actionReadAll()
    {
        Yii::$app->services->wrokerNotify->readAll(Yii::$app->user->id);

        return $this->message('全部设为已读成功', $this->redirect(['remind']));
    }
}
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-14 15:38:47
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 16:26:38
 * @Description: 
 */

namespace backend\widgets\monitornotify;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\backend\MonitorNotify;
use backend\controllers\BaseController;

/**
 * Class NotifyController
 * @package common\widgets\notify
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyController extends BaseController
{
    protected $view = '@backend/widgets/monitornotify/views/';



    /**
     * 提醒
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionRemind()
    {
        $searchModel = new SearchModel([
            'model' => MonitorNotify::class,
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
            ->andWhere(['>', 'status', StatusEnum::DISABLED]);

        return $this->render($this->view . $this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionView($id)
    {
        $model = MonitorNotify::findOne($id);

        return $this->renderAjax($this->view . $this->action->id,[
            'model' => $model,
        ]);
    }

    public function actionDestroy($id){
        $model = MonitorNotify::findOne($id);

        $model->status = StatusEnum::DISABLED;
        return $model->save()
        ? $this->redirect(['remind'])
        : $this->message($this->getError($model), $this->redirect(['remind']), 'error');
    }

    /**
     * @return mixed
     */
    public function actionReadAll()
    {
        Yii::$app->services->backendNotify->readAll(Yii::$app->user->id);

        return $this->message('全部设为已读成功', $this->redirect(['remind']));
    }
}
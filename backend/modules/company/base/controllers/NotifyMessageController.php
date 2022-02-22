<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 09:21:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 09:55:45
 * @Description: 
 */
namespace backend\modules\company\base\controllers;

use Yii;
use yii\web\Response;
use common\enums\StatusEnum;
use common\traits\Curd;
use common\models\base\SearchModel;
use common\models\worker\Notify;
use backend\modules\company\base\forms\NotifyMessageForm;
use backend\controllers\BaseController;

/**
 * Class NotifyMessageController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyMessageController extends BaseController
{
    use Curd;

    /**
     * @var \yii\db\ActiveRecord
     */
    public $modelClass = Notify::class;

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['content'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => Notify::TYPE_MESSAGE])
            ->with('messageMember','senderForMember');

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $model = new NotifyMessageForm();
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return Yii::$app->services->workerNotify->createMessage($model->content, 0, $model->toManagerId)
                ? $this->redirect(['index'])
                : $this->message('创建失败', $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}
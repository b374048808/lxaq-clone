<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 09:21:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 09:39:49
 * @Description: 
 */

namespace backend\modules\company\base\controllers;

use Yii;
use common\enums\StatusEnum;
use common\traits\Curd;
use common\models\base\SearchModel;
use common\models\worker\Notify;
use backend\modules\company\base\forms\NotifyAnnounceForm;
use backend\controllers\BaseController;

/**
 * Class NotifyAnnounceController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyAnnounceController extends BaseController
{
    use Curd;

    /**
     * @var Notify
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
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => Notify::TYPE_ANNOUNCE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        $model->type = Notify::TYPE_ANNOUNCE;
        $model->sender_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ?$this->redirect(['index'])
                :$this->message('发布失败！', $this->redirect(['index']), 'error');;
        }   

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = NotifyAnnounceForm::findOne($id)))) {
            $model = new NotifyAnnounceForm();

            return $model->loadDefaultValues();
        }

        return $model;
    }
}
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-06 10:27:19
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-21 18:38:55
 * @Description: 
 */

namespace backend\modules\monitor\data\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\models\base\SearchModel;
use common\models\monitor\project\log\ValueLog;
use common\models\monitor\project\point\Value;
use common\models\monitor\project\Point;
use yii\data\Pagination;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ValueController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Value::class;

    /**
     * 首页
     * 
     * @return mixed
     */

    public function actionIndex($state)
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC,
                'event_time' => SORT_DESC,
            ],
            'relations' => ['parent' => ['type']],
            'partialMatchAttributes' => ['parent.type'],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere([Value::tableName() . '.state' => $state])
            ->andWhere(['>', Value::tableName() . '.status', StatusEnum::DISABLED]);
        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'state' => $state,
        ]);
    }

    /**
     * ajax编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id');
        $model = $this->findModel($id);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->event_time = strtotime($model->event_time);
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }



    public function actionView($id)
    {
        $model = $this->findModel($id);

        // 历史修改记录
        $logModel = ValueLog::find()
            ->with('user')
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('id desc')
            ->asArray()
            ->all();

        return $this->renderAjax('view', [
            'model' => $model,
            'log'   => $logModel,
        ]);
    }

    /**
     * 还原
     *
     * @param $id
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::ENABLED;
        if ($model->save()) {
            return $this->message("还原成功", $this->redirect(['recycle']));
        }

        return $this->message("还原失败", $this->redirect(['recycle']), 'error');
    }

    /**
     * 回收站
     *
     * @return mixed
     */
    public function actionRecycle()
    {
        $data = $this->modelClass::find()
            ->where(['<', 'status', StatusEnum::DISABLED]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionUpdateAll()
    {
        $request = Yii::$app->request;

        $ids = $request->post('data', []);
        $warn = $request->post('warn', null);
        $state = $request->post('state', ValueStateEnum::ENABLED);
        Yii::$app->response->format =  yii\web\Response::FORMAT_JSON;
        if ($warn) {
            return Value::updateAll(['warn' => WarnEnum::SUCCESS], ['in', 'id', $ids]);
        } else if ($state) {
            return Value::updateAll(['state' => $state], ['in', 'id', $ids]);
        }
        return true;
    }

    public function actionDeleteAll()
    {
        $request = Yii::$app->request;

        $ids = $request->post('data', []);

        return Value::updateAll(['status' => StatusEnum::DELETE], ['in', 'id', $ids]);
    }

    /**
     * 审核
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxState()
    {
        $request = Yii::$app->request;

        $id = $request->get('id');
        $model = $this->findModel($id);
        $pointModel = Point::findOne($model->pid);

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            if ($model->warn > $pointModel->warn) {
                // 触发监测点报警
                $pointModel->warn = $model->warn;
                $pointModel->save();
            }
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        return $this->renderAjax('ajax-state', [
            'model' => $model,
        ]);
    }
}

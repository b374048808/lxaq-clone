<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-29 07:30:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 15:43:03
 * @Description: 
 */

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\models\monitor\project\log\WarnLog;
use common\models\monitor\project\point\Warn;
use common\models\monitor\project\warn\Feedback;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class WarnController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Warn::class;
    /**
     * 首页
     * 
     * @return mixed
     */


    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid','');

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'sort'  => SORT_DESC,
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andFilterWhere(['pid' => $pid])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    } 


    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }



    public function actionAjaxList($pid)
    {
        $query = Warn::find()
            ->where(['pid' => $pid])
            ->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('ajax-list',[
            'dataProvider' => $dataProvider
        ]);
    }

    

    /**
     * 详情
     *
     * @param number id
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {

        $model = $this->findModel($id);

        $data = Feedback::find()
            ->with('user')
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('sort ASC, id DESC')
            ->asArray()
            ->all();
        $fileModel = new Feedback();
        $fileModel->pid = $id;
        // ajax 校验
        $this->activeFormValidate($fileModel);
        if ($fileModel->load(Yii::$app->request->post())) {
            return $fileModel->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($fileModel), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        return $this->render($this->action->id, [
            'model' => $model,
            'data'  => $data,
            'fileModel' => $fileModel
        ]);
    }

    public function actionLogView($id)
    {
        $query = WarnLog::find()
            ->where(['pid' => $id])
            ->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->renderAjax('log-view',[
            'dataProvider' => $dataProvider
        ]);
    }

}

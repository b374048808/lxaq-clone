<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:39:43
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 17:16:59
 * @Description: 
 */

namespace backend\modules\sim\vlist\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\console\iot\ali\Device;
use common\models\base\SearchModel;
use common\models\sim\renewal\Log;
use common\models\sim\vlist\Card;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CardController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Card::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['iccid'], // 模糊查询// 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
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
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $model->active_time = strtotime($model->active_time);
            $model->expiration_time = strtotime($model->expiration_time);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        $model->active_time =  $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', $model->active_time);
        $model->expiration_time =  $model->isNewRecord ? date('Y-m-d', strtotime('+1 year')) : date('Y-m-d', $model->expiration_time);
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $request  = Yii::$app->request;
        $model = $this->findModel($id);

        $query = Log::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED]);
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 10
            ]
        ]);

        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider'  => $dataProvider,
        ]);
    }
}

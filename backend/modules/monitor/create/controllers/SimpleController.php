<?php

namespace backend\modules\monitor\create\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\monitor\create\Child;
use common\models\monitor\create\Simple;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SimpleController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Simple::class;
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
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }


    /**
     * 查看详情
     * @param number $id
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new SearchModel([
            'model' => Child::class,
            'scenario' => 'default',
            'relations' => ['point' => ['title']],
            'partialMatchAttributes' => ['point.title'],
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere([Child::tableName() . '.simple_id' => $id]);

        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 单个监测点生成随机数
     * 
     * @param number $id
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionRend($id)
    {
        $childModel = Child::findOne($id);
        return Yii::$app->services->createSimple->setOneValue($id)
            ? $this->redirect(['view', 'id' => $childModel['simple_id']])
            : $this->message('生成失败！', $this->redirect(['view', 'id' => $childModel['simple_id']]), 'error');
    }
}

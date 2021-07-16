<?php
namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\monitor\project\Item;
use common\models\base\SearchModel;
use common\models\monitor\project\House;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ItemController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Item::class;
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
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $model->expiration_time = strtotime($model->expiration_time);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        $model->expiration_time =  $model->isNewRecord?date('Y-m-d', strtotime('+1 year')):date('Y-m-d',$model->expiration_time);
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }


     /**
     * 回收站
     * 
     * @return mixed
     */
    public function actionRecycle()
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
            ->andWhere(['=', 'status', StatusEnum::DELETE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 还原
     * 
     * @param int
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = Item::findOne($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
                ? $this->redirect(['recycle'])
                : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
    }

    /**
     * 详情|项目下所有房屋列表
     *
     * @return mixed|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $searchModel = new SearchModel([
            'model' => House::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['item_id' => $id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);


        return $this->render($this->action->id, [
            'model' => $model,
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }


}

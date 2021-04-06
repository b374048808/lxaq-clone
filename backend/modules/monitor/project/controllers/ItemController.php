<?php
namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\monitor\project\Item;
use common\models\base\SearchModel;

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

}

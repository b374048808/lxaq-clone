<?php
namespace backend\modules\member\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\models\member\Ground;
use common\models\member\GroundMap;
use Swoole\Http\Status;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class GroundController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Ground::class;
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
        $query = $this->modelClass::find()
            ->orderBy('sort asc, id asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'menuDropDownList' => Yii::$app->services->houseGround->getDropDown($id),
        ]);
    }

    /**
     * 详情
     * @param number id
     * @return mixed|\yii\web\Response
     * @throws \yii\base\ExitException 
     */    
    public function actionView($id)
    {
        $searchModel = new SearchModel([
            'model' => GroundMap::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->where(['ground_id' => $id])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->orderBy('sort desc, created_at desc');;

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

}

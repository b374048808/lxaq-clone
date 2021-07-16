<?php
namespace backend\modules\monitor\rule\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\monitor\project\Item;
use common\models\monitor\rule\Rule;
use yii\data\ActiveDataProvider;
use common\models\monitor\rule\Child;
use common\models\monitor\rule\RuleItem;

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
    public $modelClass = Rule::class;
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
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        $query = Child::find()
            ->where(['rule_id' => $id])
            ->orderBy('id desc');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        $searchModel = new SearchModel([
            'model' => RuleItem::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $itemProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $itemProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['pid' => $id]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'model' => $model,
            'itemProvider' => $itemProvider,
            'searchModel' => $searchModel,
        ]);
    }
    

}

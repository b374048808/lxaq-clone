<?php
namespace backend\modules\monitor\rule\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\monitor\project\House;
use common\models\monitor\rule\Child;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ChildController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Child::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $rule_id = $request->get('rule_id', '');
        $searchModel = new SearchModel([
            'model' =>$this->modelClass,
            'scenario' => 'default',
            'relations' => ['house' => ['title']],
            'partialMatchAttributes' => ['house.title'],
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', Child::tableName().'.status', StatusEnum::DISABLED])
            ->andFilterWhere(['rule_id' => $rule_id]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'rule_id'   => $rule_id
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
        $rule_id = $request->get('rule_id', NULL);
        $title = $request->get('title', NULL);

        $query = House::find()
            ->where(['not in', 'id', Child::getHouseMap($rule_id)])
            ->andFilterWhere(['like','title',$title])
            ->orderBy('id desc');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);


        return $this->renderAjax('ajax-edit', [
            'dataProvider' => $dataProvider,
            'rule_id' => $rule_id
        ]);
    }


    public function actionAddHouse()
    {

        $request = Yii::$app->request;
        $rule_id = $request->get('rule_id', NULL);
        $data = $request->post('data', []);

        return Child::addHouses($rule_id, $data);
    }

    public function actionDeleteAll()
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);
        return Child::deleteAll(['in', 'id', $data]);
    }

}

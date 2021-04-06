<?php
namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\monitor\project\House;
use common\models\base\SearchModel;
use common\models\monitor\project\Item;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class HouseController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = House::class;
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
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->render($this->action->id, [
            'model' => $model,
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
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->referrer();
        }
        return $this->render($this->action->id, [
            'model' => $model,
            'cates' => Item::getDropDown()
        ]);
    }


}

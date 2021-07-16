<?php

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\models\monitor\project\Ground;
use common\models\monitor\project\GroundMap;
use common\models\monitor\project\House;
use Swoole\Http\Status;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class GroundMapController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = GroundMap::class;
    /**
     * 首页
     * 
     * @return mixed
     */


    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex($id)
    {
        $groundModel = Ground::findOne($id);
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
            ->orderBy('sort desc');;

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'groundModel' => $groundModel,
            'ground_id' => $id
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
        $ground_id = $request->get('ground_id', NULL);
        $title = $request->get('title', NULL);

        $query = House::find()
            ->where(['not in', 'id', GroundMap::getHouseMap($ground_id)])
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
            'ground_id' => $ground_id
        ]);
    }


/**
     * 批量添加房屋
     * 
     * @param number id
     * @param array data
     * @return boole
     * @throws: boole
     */
    public function actionAddHouse()
    {

        $request = Yii::$app->request;
        $ground_id = $request->get('ground_id', NULL);
        $data = $request->post('data', []);

        return GroundMap::addHouses($ground_id, $data);
    }


    /**
     * 批量删除房屋
     * 
     * @param number member_id
     * @param array data
     * @return boole
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteAll()
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);
        return GroundMap::deleteAll(['in', 'id', $data]);
    }
    

}

<?php

namespace backend\modules\member\base\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\member\base\Ground;
use common\models\member\base\HouseMap;
use common\models\monitor\project\House;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class HouseMapController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = HouseMap::class;
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
        $model = Ground::findOne($id);

        $query = HouseMap::find()
            ->andWhere(['ground_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'house_id',
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'model' => $model,
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
            ->where(['not in', 'id', HouseMap::getHouseMap($ground_id)])
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




    public function actionAddHouse()
    {

        $request = Yii::$app->request;
        $ground_id = $request->get('ground_id', NULL);
        $data = $request->post('data', []);

        return HouseMap::addHouses($ground_id, $data);
    }



    public function actionDeleteAll($ground_id)
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);
        return HouseMap::deleteAll(['and', ['ground_id' => $ground_id], ['in','house_id',$data]]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($ground_id, $house_id)
    {
        if (HouseMap::deleteAll(['ground_id' => $ground_id,'house_id' => $house_id])) {
            return $this->message("删除成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("删除失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }
}

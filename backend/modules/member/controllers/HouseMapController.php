<?php

namespace backend\modules\member\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\member\HouseMap;
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
        $query = HouseMap::find()
            ->andWhere(['member_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'house_id',
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'id' => $id,
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
        $member_id = $request->get('member_id', NULL);
        $title = $request->get('title', NULL);

        $query = House::find()
            ->where(['not in', 'id', HouseMap::getHouseMap($member_id)])
            ->andFilterWhere(['like', 'title', $title])
            ->orderBy('id desc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);


        return $this->renderAjax('ajax-edit', [
            'dataProvider' => $dataProvider,
            'member_id' => $member_id
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
        $member_id = $request->get('member_id', NULL);
        $data = $request->post('data', []);

        return HouseMap::addHouses($member_id, $data);
    }


    /**
     * 批量删除房屋
     * 
     * @param number member_id
     * @param array data
     * @return boole
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteAll($member_id)
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);
        return HouseMap::deleteAll(['and', ['member_id' => $member_id], ['in', 'house_id', $data]]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($member_id, $house_id)
    {
        if (HouseMap::deleteAll(['member_id' => $member_id, 'house_id' => $house_id])) {
            return $this->message("删除成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("删除失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }
}

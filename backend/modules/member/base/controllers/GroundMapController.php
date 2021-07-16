<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-28 15:34:14
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-04-30 08:52:29
 * @Description: 分组详情
 */

namespace backend\modules\member\base\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\member\base\Ground;
use common\models\member\base\GroundMap;
use common\models\member\Member;
use yii\data\ActiveDataProvider;

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
     * @param id
     * @return mixed|mixed
     */
    public function actionIndex($id)
    {
        $model = Ground::findOne($id);
        $query = GroundMap::find()
            ->andWhere(['ground_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'key' => 'member_id',
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
     * @param num
     * @return mixed|number
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $ground_id = $request->get('ground_id', NULL);
        $username = $request->get('username', NULL);

        $query = Member::find()
            ->where(['not in', 'id', GroundMap::getMemberMap($ground_id)])
            ->andFilterWhere(['like', 'username', $username])
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
     * @param number ground_id
     * @param array data
     * @return boole
     * @throws: boole
     */
    public function actionAddHouse()
    {

        $request = Yii::$app->request;
        $ground_id = $request->get('ground_id', NULL);
        $data = $request->post('data', []);

        return GroundMap::addMembers($ground_id, $data);
    }


    /**
     * 批量删除房屋
     * 
     * @param number member_id
     * @param array data
     * @return boole
     * @throws \yii\db\StaleObjectException
     */
    public function actionDeleteAll($ground_id)
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);
        return GroundMap::deleteAll(['and', ['ground_id' => $ground_id], ['in', 'member_id', $data]]);
    }

    /**
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($ground_id, $member_id)
    {
        if (GroundMap::deleteAll(['ground_id' => $ground_id, 'member_id' => $member_id])) {
            return $this->message("删除成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("删除失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }
}

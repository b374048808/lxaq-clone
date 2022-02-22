<?php

namespace backend\modules\monitor\service\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\models\monitor\project\Ground;
use common\models\monitor\project\GroundMap;
use common\models\monitor\project\House;
use common\models\monitor\project\house\Report;
use common\models\monitor\project\service\Map;
use common\models\monitor\project\service\ReportMap;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceMapController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Map::class;
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
    public function actionHouseList()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);
        $title = $request->get('title', NULL);

        $query = House::find()
            ->where(['not in', 'id', Map::getHouseIds($pid)])
            ->andFilterWhere(['like', 'title', $title])
            ->orderBy('id desc');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);


        return $this->renderAjax($this->action->id, [
            'dataProvider' => $dataProvider,
            'pid' => $pid
        ]);
    }


    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxReport($id)
    {
        $mapModel = $this->findModel($id);
        $model = new Report();

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $model->pid = $mapModel->map_id;
            $model->verify = VerifyEnum::PASS;
            if ($model->save()) {
                ReportMap::addMap($id, [$model->attributes['id']]);
                # code...
                return $this->redirect(Yii::$app->request->referrer);
            }
            return $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax('ajax-report', [
            'model' => $model,
            'id' => $id
        ]);
    }

    /**
     * 上传报告
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $query = ReportMap::find()
            ->where(['service_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider'  => $dataProvider
        ]);
    }

    /**
     * @return mixed|string|\yii\console\Response|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id

        // ajax 验证
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 删除报告
     * 
     * @param number id
     * @param array data
     * @return boole
     * @throws: boole
     */
    public function actionDeleteReport($service_id, $report_id)
    {

        return ReportMap::deleteAll(['and', ['service_id' => $service_id], ['report_id' => $report_id]])
            ? $this->redirect(Yii::$app->request->referrer)
            : $this->message('移除失败！', $this->redirect(Yii::$app->request->referrer), 'error');
    }

    /**
     * 批量删除报告
     * 
     * @param number id
     * @param array data
     * @return boole
     * @throws: boole
     */
    public function actionDelReports($id)
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);
        return ReportMap::deleteAll(['and',['service_id' => $id],['in', 'report_id', $data]]);
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
        $pid = $request->get('pid', NULL);
        $data = $request->post('data', []);

        return Map::addHouses($pid, $data);
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
        return Map::deleteAll(['in', 'id', $data]);
    }
}

<?php

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\monitor\ItemStepsEnum;
use common\enums\StatusEnum;
use common\models\monitor\project\Item;
use common\models\base\SearchModel;
use common\models\monitor\project\House;
use common\models\monitor\project\house\ItemMap;
use common\models\monitor\project\item\Contract;
use common\models\monitor\project\item\VerifyLog;
use common\models\worker\Worker;
use yii\data\ActiveDataProvider;

/**
 * 项目合同
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ItemContractController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Contract::class;
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
     * 回收站
     * 
     * @return mixed
     */
    public function actionRecycle()
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
            ->andWhere(['=', 'status', StatusEnum::DELETE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 还原
     * 
     * @param int
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = Item::findOne($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
            ? $this->redirect(['recycle'])
            : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
    }

    /**
     * 详情|项目下所有房屋列表
     *
     * @return mixed|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->renderAjax($this->action->id, [
            'model' => $model,
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
            $model->event_time = strtotime($model->event_time);
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        $model->event_time = $model->event_time?date('Y-m-d',$model->event_time):'';
        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'members' => Worker::getMap(),
        ]);
    }

    public function actionSteps($id, $step = true)
    {

        return  Item::updateAllCounters(['steps' => $step?1:-1], ['id' => $id])
            ? $this->redirect(['view', 'id' => $id])
            : $this->message('系统繁忙！', $this->redirect(['view', 'id' => $id]), 'error');
    }

    


    public function actionContract($pid){
        $request = Yii::$app->request;

        $model = new Contract();
        if($model->load($request->post())){
            $model->pid = $pid;
            $model->event_time = strtotime($model->event_time);
            if ($model->save()) {
                Item::updateAllCounters(['steps' => 1], ['id' => $pid]);
                return $this->redirect(['view', 'id' => $pid]);
            }else{
                return $this->message($this->getError($model), $this->redirect(['view', 'id' => $pid]), 'error');
            }
        }
        return  $this->message('系统繁忙！', $this->redirect(['view', 'id' => $pid]), 'error');
    }


    public function actionAjaxHouse($id)
    {
        // 已选择房屋列表
        $houseIds = ItemMap::getHouseIds($id);


        $query = House::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['not in', 'id', $houseIds]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->renderAjax($this->action->id, [
            'dataProvider' => $dataProvider,
            'id' => $id,
        ]);
    }
}

<?php
namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\enums\ValueTypeEnum;
use common\models\monitor\project\Point;
use common\models\base\SearchModel;
use common\helpers\ResultHelper;
use common\models\monitor\project\log\WarnLog;
use common\enums\WarnEnum;
use common\models\monitor\project\point\HuaweiMap;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PointController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Point::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid',null);
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
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['pid' => $pid]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'pid'   => $pid
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
        $request  = Yii::$app->request;
        $model = $this->findModel($id);
        $valueType = $request->get('valueType',ValueTypeEnum::AUTOMATIC);

        
        return $this->render($this->action->id, [
            'model' => $model,
            'valueType' => $valueType,
        ]);
    }

    public function actionAjaxState($pid)
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);
        $model = new WarnLog();
        $model->pid = $pid?:$model->pid;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $pointModel = Point::findOne($pid);
            $model->warn = $pointModel->warn;
            $pointModel->warn = WarnEnum::SUCCESS;
            $pointModel->save();
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        return $this->renderAjax('ajax-state', [
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
        $id = $request->get('id', '');
        $pid = $request->get('pid', NULL);
        $model = $this->findModel($id);
        $model->pid = $pid?:$model->pid;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

    /**
     * 监测点指定时间内数据
     *
     * @param number type
     * @return json|ResultHelper
     */
    public function actionValueBetweenChart($type, $id, $valueType)
    {
        $model = $this->findModel($id);
        $data = Yii::$app->services->pointValue->getBetweenChartStat($type ,$id,$valueType);

        return ResultHelper::json(200, '获取成功', $data);
    }

}

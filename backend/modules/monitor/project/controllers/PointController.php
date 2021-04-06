<?php
namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\monitor\project\Point;
use common\models\base\SearchModel;
use common\helpers\ResultHelper;

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
        $model = $this->findModel($id);

        return $this->render($this->action->id, [
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
                ? $this->redirect(['index','pid' => $pid])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

    /**
     * 监测点指定时间内数据
     *
     * @param $type
     * @return array
     */
    public function actionValueBetweenChart($type,$id)
    {
        $data = Yii::$app->services->pointValue->getBetweenChartStat($type,$id);

        return ResultHelper::json(200, '获取成功', $data);
    }

}

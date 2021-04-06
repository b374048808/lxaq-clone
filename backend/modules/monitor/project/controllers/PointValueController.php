<?php
namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\enums\PointEnum;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Angle;
use common\models\base\SearchModel;
use common\helpers\ResultHelper;
use common\helpers\ArrayHelper;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PointValueController extends BaseController
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
        
        $class = PointEnum::getModel($pid);

        $searchModel = new SearchModel([
            'model' => $class,
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
            'pid' => $pid
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
        $model = new Angle;
        $model->pid = $pid?:$model->pid;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $model->event_time = strtotime($model->event_time);
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
    public function actionValueBetweenCount($type,$id)
    {
        $data = Yii::$app->services->pointValue->getBetweenCountStat($type,$id);

        return ResultHelper::json(200, '获取成功', $data);
    }


    
    /**
     * 服务器探针
     *
     * @return array|string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionProbe()
    {
        $request = Yii::$app->request;
        $time = $request->get('time');
        $id = $request->get('id');
        $type = $request->get('type');
        $info = $this->getProbeInfo($time,$id,$type);
        if (Yii::$app->request->isAjax) {
             return ResultHelper::json(200, '获取成功', $info);            
        }

        return $this->render('probe', [
            'info' => $info,
        ]);
    }

    /**
     * 探针
     *
     * @return mixed
     * @throws \yii\web\NotFoundHttpException
     */
    protected function getProbeInfo($time, $id, $type)
    {
        $status =true;
        $model = PointEnum::getModel($type);
        $oneModel = Point::findOne($id);
        $pointModel = Point::find()
            ->where(['pid'=>$oneModel->pid])
            ->andWhere(['=','status' ,StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $data = [];
        foreach ($pointModel as $key => $value) {
            $data[$value['title']] = $model::find()
                ->where(['pid' => $id])
                ->andWhere(['>','status' ,StatusEnum::DISABLED])
                ->andWhere(['between','event_time',$time,time()])
                ->asArray()
                ->one();
            if (!$data[$value['title']]) {
                $status=false;
            }
        }
        
        // $models = ArrayHelper::index($models,'title');
        // 递增时间

        return [
            'status' => $status,
            'chartTime' => date('H:i:s',$time),
            'time' => time()
        ];
    }

}

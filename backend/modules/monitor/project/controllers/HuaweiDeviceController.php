<?php

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\console\iot\huawei\Device as HuaweiDevice;
use common\models\monitor\project\point\HuaweiMap;
use common\models\monitor\project\Point;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class HuaweiDeviceController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = HuaweiMap::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', '');
        $pointModel = Point::findOne($pid);
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
            ->andWhere(['point_id' => $pid])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'pointModel' => $pointModel
        ]);
    }

    /**
     * 编辑/创建
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        $model->point_id = $request->get('point_id', '') ?: $model->point_id;
        if ($model->load(Yii::$app->request->post())) {
            $model->install_time = strtotime($model->install_time);
            return $model->save()
                ? $this->redirect(['index', 'pid' => $model['point_id']])
                : $this->message($this->getError($model), $this->redirect(['index', 'pid' => $model['point_id']]), 'error');
        }
        // 不属于新建时，转化坐标
        if (!$model->isNewRecord) {
            $model->lnglat['lng'] = $model->lng;
            $model->lnglat['lat'] = $model->lat;
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'devices' => HuaweiDevice::getDropDown()
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

        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}

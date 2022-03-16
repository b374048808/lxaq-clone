<?php

namespace backend\modules\console\huawei\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use backend\modules\console\huawei\forms\DirectiveForm;
use common\enums\StatusEnum;
use common\models\console\iot\huawei\Device;
use common\models\base\SearchModel;
use common\models\console\iot\huawei\Directive;
use common\models\console\iot\huawei\Service;
use common\models\monitor\project\point\HuaweiMap;
use common\models\sim\vlist\Card;
use common\models\monitor\project\Point;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DeviceController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Device::class;
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
            'partialMatchAttributes' => ['number', 'card'], // 模糊查询
            'defaultOrder' => ['id' => SORT_DESC],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);

        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'onLine' => Yii::$app->services->huaweiDevice->getOnLineCount()
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
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            if ($model->over_time)
                $model->over_time = strtotime($model->over_time);
            return $model->save()
                ? $this->redirect([Yii::$app->request->referrer])
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'cards' => Card::getMap()
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

        // 设备使用情况
        $pointIds = HuaweiMap::getPointColumn($id);
        $pointModel = Point::find()
            ->with(['house', 'newValue'])
            ->where(['in', 'id', $pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'model' => $model,
            'pointModel'    => $pointModel

        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAttr($id)
    {
        $request  = Yii::$app->request;
        $model = $this->findModel($id);

        global $_id;
        $_id = $id;
        $services = Service::find()
            ->with(['attr', 'newAttr' => function ($queue) {
                global $_id;
                $queue->andWhere(['pid' => $_id]);
            }])
            ->where(['pid' => $model->pid])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($services as $key => $value) {
            $services[$key]['newAttr']['value'] = json_decode($value['newAttr']['value'], true);
        }
        return $this->render($this->action->id, [
            'id' => $id,
            'services' => $services,
        ]);
    }

    /**
     * 查看命令行
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionDirective($id)
    {
        $request  = Yii::$app->request;
        $model = Device::find()
            ->with('directive')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    public function actionDirectiveAll()
    {
        $request = Yii::$app->request;
        $key = $request->post('data');
        $content = $request->post('content');
        Yii::$app->response->format =  yii\web\Response::FORMAT_JSON;

        return Yii::$app->services->huaweiDirective->sendAll($key, $content);
    }

    /**
     * 查看命令行
     *
     * @param number id
     * @param number directive_id
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionGetDirective($id, $directive_id)
    {
        $model = $this->findModel($id);
        $request = Yii::$app->request;
        $directiveModel = Directive::findOne($directive_id);
        $formModel = new DirectiveForm();

        // 判断是否选择带参命令
        if (strpos($directiveModel->content, '@RES') && !$request->isPost) {
            return $this->renderAjax($this->action->id, [
                'model' => $formModel,
                'id' => $model->id,
                'directive_id' => $directiveModel->id,
            ]);
        }
        // ajax 校验
        $this->activeFormValidate($formModel);
        if ($formModel->load($request->post())) {
            return Yii::$app->services->huaweiDirective->send($id, $directive_id, $formModel->value)
                ? $this->redirect(['directive', 'id' => $model->id])
                : $this->message($this->getError($formModel), $this->redirect(['directive', 'id' => $model->id]));
        }
        // 没有携带参数
        return Yii::$app->services->huaweiDirective->send($id, $directive_id)
            ? $this->message('下发成功！', $this->redirect(['directive', 'id' => $model->id]), 'success')
            : $this->message('下发失败！', $this->redirect(['directive', 'id' => $model->id]), 'error');
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
            'relations' => ['product'  => ['name']],    //产品名称
            'partialMatchAttributes' => ['product_name', 'title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['=', Device::tableName() . '.status', StatusEnum::DELETE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'onLine' => Yii::$app->services->aliDevice->getOnLineCount()
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
        $model = $this->findModel($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
            ? $this->redirect(['recycle'])
            : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
    }
}

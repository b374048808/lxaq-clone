<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-24 10:48:57
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 17:10:16
 * @Description: 
 */

namespace backend\modules\company\service\controllers;

use Yii;
use common\models\base\SearchModel;
use common\traits\Curd;
use common\models\worker\Worker;
use common\enums\StatusEnum;
use common\enums\AppEnum;
use common\helpers\ArrayHelper;
use backend\controllers\BaseController;
use BaconQrCode\Writer;
use common\models\company\service\Service;
use common\models\company\service\Steps;
use common\models\company\service\Template;
use common\models\rbac\AuthRole;
use common\models\company\service\StepsOn;
use yii\data\ActiveDataProvider;

/**
 * Class WorkerController
 * @package addons\Merchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceController extends BaseController
{
    use Curd;

    /**
     * @var Worker
     */
    public $modelClass = Service::class;

    public function init()
    {
        parent::init();
    }

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            // 'partialMatchAttributes' => ['username', 'mobile'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 详情
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);
        
        $request = Yii::$app->request;

        $query = StepsOn::find()
            ->andWhere(['pid' => $id])
            ->orderBy('id asc');

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);


        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            if ($model->isNewRecord) {
                $db = Yii::$app->db;
                // 在主库上启动事务
                $transaction = $db->beginTransaction();
                try {
                    if (!$model->save()) {
                        return $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
                    }
                    // 新创建时
                    $stepModel = Steps::find()
                        ->where(['pid' => $model->pid])
                        ->andWhere(['status' => StatusEnum::ENABLED])
                        ->orderBy('sort asc', 'id asc')
                        ->asArray()
                        ->all();
                    StepsOn::addValue($model->attributes['id'], $stepModel);
                    $transaction->commit();
                } catch (\Exception $e) {
                    $transaction->rollBack();
                    throw $e;
                } catch (\Throwable $e) {
                    $transaction->rollBack();
                    throw $e;
                }
                return $this->redirect('index');
            }
            return $model->save()
                ? $this->redirect('index')
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        $workers = Worker::find()
            ->with('role')
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($workers as $key => &$value) {
            if ($value['role']['pid'] > 0) {
                $authModel = AuthRole::findOne($value['role']['pid']);
                $value['role']['title'] = $authModel['title'] . '/' . $value['role']['title'];
                # code...
            }
            $title = Yii::$app->services->rbacAuthRole->getDropDownForEdit(AppEnum::WORKER, $value['role']['id']);
            $value['roleName'] = $value['username'] . '       ' . $value['role']['title'];
        }
        unset($value);
        $roles = ArrayHelper::map($workers, 'id', 'roleName');


        return $this->render($this->action->id, [
            'model' => $model,
            'roles' => $roles,
            'templates' => Template::getDropDown()
        ]);
    }






    /**
     * 伪删除
     *
     * @param $id
     * @return mixed
     */
    public function actionDestroy($id)
    {
        if (!($model = $this->modelClass::findOne($id))) {
            return $this->message("找不到数据", $this->redirect(['index', 'merchant_id' => $this->merchant_id]), 'error');
        }

        $model->status = StatusEnum::DELETE;
        if ($model->save()) {
            return $this->message("删除成功", $this->redirect(['index', 'merchant_id' => $this->merchant_id]));
        }

        return $this->message("删除失败", $this->redirect(['index', 'merchant_id' => $this->merchant_id]), 'error');
    }
}

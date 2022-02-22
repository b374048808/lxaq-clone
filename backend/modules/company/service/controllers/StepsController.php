<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-24 10:48:57
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-25 10:32:29
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
use common\models\company\service\Steps;
use common\models\rbac\AuthRole;

/**
 * Class WorkerController
 * @package addons\Merchants\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class StepsController extends BaseController
{
    use Curd;

    /**
     * @var Worker
     */
    public $modelClass = Steps::class;

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
        $pid = $request->get('pid','');
        $model = $this->findModel($id);
        $model->pid = $pid?:$model['pid'];
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
            ? $this->redirect(Yii::$app->request->referrer)
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
                $value['role']['title'] = $authModel['title'].'/'.$value['role']['title'];
                # code...
            }
            $title = Yii::$app->services->rbacAuthRole->getDropDownForEdit(AppEnum::WORKER,$value['role']['id']);
            $value['roleName'] = $value['username'].'       '.$value['role']['title'];

        }
        unset($value);
        $roles = ArrayHelper::map($workers, 'id','roleName');
    
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'roles' => $roles
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
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-08 15:00:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-08 15:36:16
 * @Description: 
 */

namespace backend\modules\company\worker\controllers;

use Yii;
use yii\data\ActiveDataProvider;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\company\staff\Dept;

/**
 * 部门管理
 *
 * Class ArticleCateController
 * @package addons\RfArticle\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DeptController extends BaseController
{
    use Curd;

    /**
     * @var Dept
     */
    public $modelClass = Dept::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Dept::find()
            ->orderBy('sort asc, created_at asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
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
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'cateDropDownList' => Dept::getDropDownForEdit($id),
        ]);
    }
}
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-29 07:30:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-02 11:22:11
 * @Description: 
 */

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\monitor\project\house\Report;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ReportController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Report::class;
    /**
     * 首页
     * 
     * @return mixed
     */


    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = $this->modelClass::find()
            ->orderBy('sort asc, id asc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
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
        $id = Yii::$app->request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

}

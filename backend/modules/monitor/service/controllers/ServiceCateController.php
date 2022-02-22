<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-22 16:15:44
 * @Description: 
 */

namespace backend\modules\monitor\service\controllers;

use common\models\monitor\project\service\ServiceCate;
use Yii;
use yii\data\ActiveDataProvider;
use common\traits\Curd;
use backend\controllers\BaseController;

/**
 * 文章分类
 *
 * Class ArticleCateController
 * @package addons\RfArticle\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceCateController extends BaseController
{
    use Curd;

    /**
     * @var ServiceCate
     */
    public $modelClass = ServiceCate::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = ServiceCate::find()
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
            'cateDropDownList' => ServiceCate::getDropDownForEdit($id),
        ]);
    }
}
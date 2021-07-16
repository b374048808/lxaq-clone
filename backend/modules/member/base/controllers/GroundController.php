<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-28 15:34:08
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-04-30 08:43:14
 * @Description: 
 */

namespace backend\modules\member\base\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\member\base\Ground;
use common\models\member\base\GroundMap;
use yii\data\ActiveDataProvider;

class GroundController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Ground::class;
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
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'menuDropDownList' => Yii::$app->services->memberGround->getDropDown($id),
        ]);
    }
}

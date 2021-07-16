<?php
namespace backend\modules\console\huawei\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\console\iot\huawei\ServiceAttr;

/**
 * 属性
 *
 * Class ServiceAttrController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceAttrController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = ServiceAttr::class;

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
        $pid = $request->get('pid', '');
        $model = $this->findModel($id);
        $model->pid = $pid?:$model->pid;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }


     /**
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->renderAjax($this->action->id, [
            'model' => ServiceAttr::findOne($id),
        ]);
    }
}

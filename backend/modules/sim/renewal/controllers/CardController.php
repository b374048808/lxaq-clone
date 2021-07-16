<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:39:43
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 15:47:47
 * @Description: 
 */

namespace backend\modules\sim\renewal\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use backend\modules\sim\renewal\forms\RenewalForm;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\sim\renewal\Log;
use common\models\sim\vlist\Card;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class CardController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Card::class;
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
            'partialMatchAttributes' => ['iccid'], // 模糊查询// 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);
        // 条件为到期日期在未来30天内
        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['<=','expiration_time',strtotime('+30 day')]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionRenewal()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = new RenewalForm();
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $cardModel = Card::findOne($id);
            $logModel = new Log();
            $logModel->pid = $id;
            $unit = 'day';
            switch ($model->unit) {
                case 1:
                    $unit = 'month';
                    break;
                case 2:
                    $unit = 'year';
                    break;
                
                default:
                    # code...
                    break;
            }
            $logModel->expiration_time = strtotime('+'.$model->number.' '.$unit.'',$cardModel['expiration_time']);
            $logModel->day = round($logModel->expiration_time - $cardModel['expiration_time'])/86400;
            return $logModel->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->renderAjax('renewal', [
            'model' => $model,
            'id' => $id,
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
        
        

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }
}

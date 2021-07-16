<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 09:00:00
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-13 15:55:00
 * @Description: 
 */

namespace backend\modules\monitor\log\controllers;

use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\models\monitor\project\rule\Log;
use backend\controllers\BaseController;
use common\models\monitor\project\log\WarnLog;
use yii\data\ActiveDataProvider;

/**
 * Class LogController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class PointWarnController extends BaseController
{

     /**
     * @var Adv
     */
    public $modelClass = WarnLog::class;
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex($pid)
    {
        $query = $this->modelClass::find()
            ->andWhere(['pid' => $pid])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('id desc');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->renderAjax('index', [
            'dataProvider' => $dataProvider,
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
        $model = Log::findOne($id);
        
        return $this->renderAjax($this->action->id, [
            'model' => $model,
        ]);
    }
}
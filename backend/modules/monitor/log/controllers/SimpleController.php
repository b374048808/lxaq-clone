<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 09:00:00
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-07 14:35:24
 * @Description: 
 */

namespace backend\modules\monitor\log\controllers;

use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\models\monitor\rule\Log;
use backend\controllers\BaseController;

/**
 * Class LogController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class SimpleController extends BaseController
{
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Log::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'created_at' => SORT_DESC,
            ],
            'relations' => ['item' => ['warn'],'house' => ['title']],
            'partialMatchAttributes' => ['item.warn','house.title','point.title'],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', Log::tableName().'.status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
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
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 09:21:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 10:55:14
 * @Description: 
 */

namespace backend\modules\company\base\controllers;

use Yii;
use common\enums\StatusEnum;
use common\traits\Curd;
use common\models\base\SearchModel;
use common\models\worker\Notify;
use backend\controllers\BaseController;
use common\models\worker\MiniMessageLog;

/**
 * Class NotifyAnnounceController
 * @package backend\modules\base\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MiniMessageController extends BaseController
{
    use Curd;

    /**
     * @var Notify
     */
    public $modelClass = MiniMessageLog::class;

    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            // 'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
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
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->renderAjax($this->action->id, [
            'model' => MiniMessageLog::findOne($id),
        ]);
    }
}
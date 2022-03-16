<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-19 14:50:10
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-23 16:51:17
 * @Description: 监测首页
 */

namespace backend\modules\monitor\lk\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\monitor\project\point\HuaweiMap;

class PointController extends BaseController
{

    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = HuaweiMap::class;
    /**
     * 首页
     *
     * @return string
     */
    public function actionIndex()
    {


        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => ['id' => SORT_DESC],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}

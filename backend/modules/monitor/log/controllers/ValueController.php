<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 14:19:39
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 14:41:30
 * @Description: 
 */
namespace backend\modules\monitor\log\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\enums\PointEnum;
use common\models\monitor\project\point\Angle;
use common\models\base\SearchModel;
use common\models\monitor\project\log\ValueLog;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ValueController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = ValueLog::class;

    
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'type' => $request->get('type',PointEnum::ANGLE)
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
            'model' => $this->findModel($id),
        ]);
    }
}
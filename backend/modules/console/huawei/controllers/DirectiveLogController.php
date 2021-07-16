<?php

namespace backend\modules\console\huawei\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\console\iot\huawei\DirectiveLog;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DirectiveLogController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = DirectiveLog::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', '');

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'relations' => ['device'  => ['number'],'directive' => ['id']],
            'partialMatchAttributes' => ['device.number'], // 模糊查询
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', DirectiveLog::tableName().'.status', StatusEnum::DISABLED])
            ->andFilterWhere([DirectiveLog::tableName().'.device_id' => $id]);
            
        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'id' => $id,
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
            'model' => DirectiveLog::findOne($id),
        ]);
    }
}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-22 08:44:52
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-20 15:47:55
 * @Description: 
 */
namespace backend\modules\console\ali\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\console\iot\ali\Value;
use TencentCloud\Cdb\V20170320\Models\TableName;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class LogController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Value::class;
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
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'relations' => ['device'  => ['number']],
            'partialMatchAttributes' => ['device.number'], // 模糊查询
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', Value::TableName().'.status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
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
            'model' => Value::findOne($id),
        ]);
    }
}

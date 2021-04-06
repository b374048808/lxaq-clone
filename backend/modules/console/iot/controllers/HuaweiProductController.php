<?php
namespace backend\modules\console\iot\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\iot\huawei\Product;
use common\models\base\SearchModel;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class HuaweiProductController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Product::class;
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
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

}

<?php
namespace backend\modules\console\huawei\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\console\iot\huawei\Product;
use common\models\base\SearchModel;
use common\models\console\iot\huawei\Service;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ProductController extends BaseController
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

    /**
     * 详情
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $services = Service::find()
            ->with(['attr'])
            ->where(['pid' => $id])
            ->andWhere(['>=','status',StatusEnum::DISABLED])
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'model' => $model,
            'services' => $services
        ]);
    }

}

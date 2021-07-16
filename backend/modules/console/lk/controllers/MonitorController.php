<?php
namespace backend\modules\console\lk\controllers;

use Yii;
use common\traits\MerchantCurd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\enums\PointEnum;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Angle;
use common\models\base\SearchModel;
use common\helpers\ResultHelper;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MonitorController extends BaseController
{
    use MerchantCurd;

    public $modelClass = '';

    /**
     * @var Adv
     */
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;

        return $this->render($this->action->id, [
        ]);
    }

    /**
     * 华为指定时间内数据
     *
     * 
     * @return array
     */
    public function actionHuaweiBetweenCount($type)
    {
        $data = Yii::$app->services->huaweiValue->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }


    /**
     * 阿里指定时间内数据
     *
     * 
     * @return array
     */
    public function actionAliBetweenCount($type)
    {
        $data = Yii::$app->services->aliValue->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }



}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-28 11:12:08
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-28 20:43:07
 * @Description: 首页
 */

namespace backend\modules\monitor\main\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\House;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\AliMap;
use common\models\monitor\project\point\HuaweiMap;
use common\helpers\ArrayHelper;
use common\models\monitor\project\log\WarnLog;
use common\models\monitor\project\point\Warn;
use common\helpers\ResultHelper;

class SiteController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = '';
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {

        $warnList = Warn::find()
            ->with(['point','house'])
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $houseCount['all'] = House::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        $houseCount['monitor'] = Point::find()
            ->groupBy('pid')
            ->count();

        $pointCount['all'] = Point::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        $huaweiMap = HuaweiMap::find()
            ->groupBy('point_id')
            ->asArray()
            ->all();
            $huaweiMap = ArrayHelper::getColumn($huaweiMap,'point_id', $keepKeys = true);
        $aliMap = AliMap::find()
            ->groupBy('point_id')
            ->asArray()
            ->all();
            $aliMap = ArrayHelper::getColumn($aliMap,'point_id', $keepKeys = true);
        $pointCount['monitor'] = count(array_merge($huaweiMap,$aliMap));
        
        $warn['all'] = WarnLog::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        $warn['deal'] = Point::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['>', 'warn', WarnEnum::SUCCESS])
            ->count();



        return $this->render($this->action->id,[
            'houseCount'    => $houseCount,
            'pointCount'    => $pointCount,
            'warn'  => $warn,
            'warnList'  => $warnList,
        ]);

    }

    /**
     * 监测点指定时间内数据
     *
     * @param number type
     * @return json|ResultHelper
     */
    public function actionWarnBetweenCount($type)
    {
        $data = Yii::$app->services->pointWarn->getBetweenChartStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

}

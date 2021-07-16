<?php

namespace backend\controllers;

use Yii;
use backend\forms\ClearCache;
use common\helpers\ResultHelper;
use backend\controllers\BaseController;
use common\enums\monitor\BellStateEnum;
use common\enums\monitor\SubscriptionActionEnum;
use common\enums\monitor\SubscriptionReasonEnum;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use common\models\monitor\project\House;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\AliMap;
use common\models\monitor\project\point\HuaweiMap;
use common\helpers\ArrayHelper;
use common\models\backend\MonitorNotify;
use common\models\console\iot\ali\Device as AliDevice;
use common\models\console\iot\ali\Value as AliValue;
use common\models\console\iot\huawei\Device;
use common\models\console\iot\huawei\Value;
use common\models\monitor\project\house\Bell;
use common\models\monitor\project\point\Value as PointValue;
use common\models\monitor\project\point\Warn;
use common\models\sim\vlist\Card;

/**
 * 主控制器
 *
 * Class MainController
 * @package backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class MainController extends BaseController
{
    /**
     * 系统首页
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->renderPartial($this->action->id, []);
    }

    /**
     * 子框架默认主页
     *
     * @return string
     */
    public function actionSystem()
    {
        $huaweiMap = HuaweiMap::find()
            ->groupBy('point_id')
            ->asArray()
            ->all();
        $huaweiMap = ArrayHelper::getColumn($huaweiMap, 'point_id', $keepKeys = true);
        $aliMap = AliMap::find()
            ->groupBy('point_id')
            ->asArray()
            ->all();
        $aliMap = ArrayHelper::getColumn($aliMap, 'point_id', $keepKeys = true);
        $pointCount = count(array_merge($huaweiMap, $aliMap));

        // 预警数
        $warn['all'] = Warn::find()
            ->where(['status' => StatusEnum::ENABLED])
            // ->andWhere(['>','state', WarnStateEnum::DISABLED])
            ->count();
        $warn['deal'] = Warn::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['state' => WarnStateEnum::AUDIT])
            ->andWhere(['>', 'warn', WarnEnum::SUCCESS])
            ->count();
        // 设备
        $huaweiDeviceCount['all'] = Device::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        $aliDeviceCount['all'] = AliDevice::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        $deviceAll['all'] = $huaweiDeviceCount['all'] + $aliDeviceCount['all'];

        $huaweiOnlineCount = Value::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['between', 'event_time', strtotime('-1 day'), time()])
            ->groupBy('pid')
            ->count();
        $aliOnlineCount = AliValue::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['between', 'event_time', strtotime('-1 day'), time()])
            ->groupBy('pid')
            ->count();
        $deviceAll['online'] = $huaweiOnlineCount + $aliOnlineCount;

        // 待审核数据
        $valueCount = PointValue::find()
            ->where(['state' => ValueStateEnum::AUDIT])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->count();

        // 提醒日历取10项
        $bell = Bell::find()
            ->where(['state' => BellStateEnum::UNFINISHED])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->count();

        // 物联网卡管理
        $card['all'] = Card::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->count();
        //未到期在线数量
        $card['deal'] = Card::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['>', 'expiration_time', time()])
            ->count();
        // 报警列表
        $warnList = Warn::find()
            ->with(['point', 'house'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['state' => WarnStateEnum::AUDIT])
            ->limit(5)
            ->orderBy('created_at DESC')
            ->asArray()
            ->all();
        // 消息管理
        $notifyModel = MonitorNotify::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->limit(10)
            ->orderBy('id desc')
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'pointCount'    => $pointCount,
            'warn'  => $warn,
            'warnList'  => $warnList,
            'deviceCount' => $deviceAll,
            'valueCount'    => $valueCount,
            'bell' => $bell,
            'card'  => $card,
            'notify'    => $notifyModel
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

    /**
     * 监测点指定时间内数据
     *
     * @param number type
     * @return json|ResultHelper
     */
    public function actionHuaweiBetweenCount($type)
    {
        $data = Yii::$app->services->huaweiValue->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 监测点指定时间内数据
     *
     * @param number type
     * @return json|ResultHelper
     */
    public function actionAliBetweenCount($type)
    {
        $data = Yii::$app->services->aliValue->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }


    /**
     * 用户指定时间内数量
     *
     * @param $type
     * @return array
     */
    public function actionMemberBetweenCount($type)
    {
        $data = Yii::$app->services->member->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 充值统计
     *
     * @param $type
     * @return array
     */
    public function actionMemberRechargeStat($type)
    {
        $data = Yii::$app->services->memberCreditsLog->getRechargeStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 用户指定时间内消费日志
     *
     * @param $type
     * @return array
     */
    public function actionMemberCreditsLogBetweenCount($type)
    {
        $data = Yii::$app->services->memberCreditsLog->getBetweenCountStat($type);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 清理缓存
     *
     * @return string
     */
    public function actionClearCache()
    {
        $model = new ClearCache();
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->message('清理成功', $this->refresh())
                : $this->message($this->getError($model), $this->refresh(), 'error');
        }

        return $this->render($this->action->id, [
            'model' => $model
        ]);
    }
}

<?php

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\enums\TimeUnitEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\House;
use common\models\base\SearchModel;
use common\models\monitor\project\Item;
use common\models\monitor\project\Point;
use common\helpers\ArrayHelper;
use common\helpers\ResultHelper;
use common\helpers\ExcelHelper;
use common\models\console\iot\huawei\Device;
use common\models\monitor\project\house\Report;
use common\models\monitor\project\point\HuaweiMap;
use common\models\monitor\project\point\Value;
use common\models\monitor\project\rule\Item as ProjectRuleItem;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class HouseController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = House::class;
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
            'partialMatchAttributes' => ['title'],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }


    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // 房屋下的各类监测点
        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->groupBy('type')
            ->asArray()
            ->all();
        $points = ArrayHelper::getColumn($pointModel, 'type', $keepKeys = true);
        // 报警规则触发器
        $query = ProjectRuleItem::find()
            ->where(['pid' => $id])
            ->orderBy('type ASC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $pointIds =  ArrayHelper::getColumn($pointModel, 'id', $keepKeys = true);

        // 设备的安装点位
        $pointModel = Point::find()
            ->with(['device', 'newValue', 'deviceMap'])
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $reportModel = Report::find()
            ->with('user')
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->limit(10)
            ->orderBy('id desc')
            ->asArray()
            ->all();


        $pointIds = House::getPointColumn($id);
        // 最新数据
        $valueModel = Value::find()
            ->with('parent')
            ->where(['in', 'pid', $pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->limit(10)
            ->orderBy('event_time desc')
            ->asArray()
            ->all();


        return $this->render($this->action->id, [
            'model' => $model,
            'points' => $points,
            'dataProvider' => $dataProvider,
            'valueList' => $valueModel,
            'pointModel' => $pointModel,
            'reportModel' => $reportModel
        ]);
    }


    /**
     * 监测点指定时间内数据
     *
     * @param type
     * @return array
     */
    public function actionPointBetweenCount($type)
    {
        $request = Yii::$app->request;
        // 监测点位类型
        $pointType = $request->get('point_type', PointEnum::ANGLE);
        // 房屋的ID，用于遍历房屋下同类型点位
        $id = $request->get('id');

        $data = Yii::$app->services->pointValue->getPointBetweenCount($type, $id, $pointType);

        return ResultHelper::json(200, '获取成功', $data);
    }

    /**
     * 点位实时数据
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionMonitor($id)
    {
        $model = $this->findModel($id);

        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->groupBy('type')
            ->asArray()
            ->all();

        $points = ArrayHelper::getColumn($pointModel, 'type');


        return $this->render($this->action->id, [
            'model' => $model,
            'points' => $points,
        ]);
    }

    public function actionReport($id)
    {
        // 提醒列表
        $query = Report::find()
            ->where(['pid' => $id])
            ->orderBy('id DESC');
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('report', [
            'id'    => $id,
            'dataProvider'  => $dataProvider
        ]);
    }

    /**
     * ajax点位实时数据
     *
     * @param number id
     * @param number type 
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionMonitorType($id, $type)
    {
        $request = Yii::$app->request;
        // 开始时间
        $start_time = $request->get('start_time', date('y-m-d H:00:00', strtotime('-1 day')));
        $info = $this->probeInfo($id, $type, $start_time ?: date('y-m-d H:00:00', strtotime('-1 day  +1 hours')));
        if (Yii::$app->request->isAjax) {
            return ResultHelper::json(200, '获取成功', $info);
        }
    }


    public function actionAjaxDevice()
    {
        $request = Yii::$app->request;
        $id = Yii::$app->request->get('id', null);
        $model = $id ? HuaweiMap::findOne($id) : new HuaweiMap();
        $model->point_id = $request->get('point_id', '') ?: $model->point_id;
        if ($model->load(Yii::$app->request->post())) {
            $model->install_time = strtotime($model->install_time);
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'devices' => Device::getDropDown()
        ]);
    }

    /**
     * 
     * 
     * @param $id
     * @param $type
     * @param $start_time
     * @return array
     */
    public function probeInfo($id, $type = PointEnum::ANGLE, $start_time)
    {
        // 遍历房屋下所有同类型监测点
        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $res = [];

        $times = $lenged = [];
        for ($i = strtotime($start_time); $i < strtotime('+1 day', strtotime($start_time)); $i += 3600) {
            array_push($times, $i);
            array_push($lenged, date('H:i', $i));
        }

        $res['times'] = $lenged;
        $res['name']  = $res['values'] = [];
        $res['time']  = $start_time;    //返回最后遍历的时间作为下次请求的开始时间
        // 遍历所有指定时间内数据
        foreach ($pointModel as $key => $value) {
            $data = [];
            $data['name'] = $value['title'];
            $res['name'][] = $value['title'];
            for ($i = 0; $i < count($times); $i++) {
                $angleModel = Value::find()
                    ->where(['pid' => $value['id']])
                    ->andWhere(['type' => $type])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['state' => ValueStateEnum::ENABLED])    //通过审核
                    ->andWhere(['between', 'event_time', $times[$i], $times[$i] + 3600])
                    ->orderBy('value ASC')
                    ->asArray()
                    ->one();
                $data['data'][] = $angleModel ? $angleModel['value'] : NULL;
            }
            array_push($res['values'], $data);
        }

        return $res;
    }

    /**
     * 数据列表
     * 
     * @return mixed
     */
    public function actionValueList($id)
    {
        $request = Yii::$app->request;
        $pointIds = House::getPointColumn($id);

        $from_date =  $request->get('from_date', date('Y-m-d', strtotime("-6 day")));
        $to_date = $request->get('to_date', date('Y-m-d'));
        $state = $request->get('state', ValueStateEnum::ENABLED);

        $searchModel = new SearchModel([
            'model' => Value::class,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'relations' => ['parent' => ['title', 'type']],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['state' => $state])
            ->andWhere(['in', Value::tableName() . '.pid', $pointIds])
            ->andFilterWhere(['between',  Value::tableName() . '.event_time', strtotime($from_date), strtotime("+1 day", strtotime($to_date))])
            ->andWhere(['>=',  Value::tableName() . '.status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'from_date' => $from_date,
            'to_date'   => $to_date,
            'id'   => $id,
            'state' => $state
        ]);
    }

    /**
     * 导出Excel
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport($id)
    {
        // [名称, 字段名, 类型, 类型规则]
        $request = Yii::$app->request;
        //默认输出一周数据
        $pointIds = House::getPointColumn($id);
        $from_date =  $request->get('from_date', date('Y-m-d', strtotime("-6 day")));
        $to_date = $request->get('to_date', date('Y-m-d'));
        $state = $request->get('state', ValueStateEnum::ENABLED);
        $model = $this->findModel($id);

        $data = Value::find()
            ->with(['parent'])
            ->where(['state' => $state])
            ->andWhere(['=', 'status', StatusEnum::ENABLED])
            ->andWhere(['in', Value::tableName() . '.pid', $pointIds])
            ->andWhere(['between', 'event_time', strtotime($from_date), strtotime("+1 day", strtotime($to_date))])
            ->asArray()
            ->all();
        foreach ($data as $key => $value) {
            $data[$key]['title'] = $value['parent']['title'];
            $data[$key]['pointType'] = PointEnum::getValue($value['parent']['type']);
        }
        $header = [
            ['点位', 'title', 'text'],
            ['类型', 'pointType', 'text'],
            ['数据类型', 'type', 'selectd', ValueTypeEnum::getMap()],
            ['数据', 'value', 'text'],
            ['报警', 'warn', 'selectd', WarnEnum::getMap()],
            ['采集时间', 'event_time', 'date', 'Y-m-d H:i:s'],
        ];
        return ExcelHelper::exportData($data, $header, $model->title . '数据导出_' . time());
    }


    /**
     * 编辑/创建
     * @param number id
     * @return mixed|array
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        // 不属于新建时，转化坐标
        if (!$model->isNewRecord) {
            $model->lnglat['lng'] = $model->lng;
            $model->lnglat['lat'] = $model->lat;
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'cates' => Item::getDropDown()
        ]);
    }

    /**
     * 回收站
     * 
     * @throws: 
     */
    public function actionRecycle()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'partialMatchAttributes' => ['title'],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['=', 'status', StatusEnum::DELETE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 还原
     * 
     * @param int
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = Item::findOne($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
            ? $this->redirect(['recycle'])
            : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
    }

    /**
     * 所有数据曲线图
     * 
     * @param number $id
     * @return mixed|array
     * @throws: 
     */
    public function actionDataChart($id)
    {
        $request = Yii::$app->request;
        $type = $request->get('type', PointEnum::ANGLE);    //类型
        $unit = TimeUnitEnum::getUnit($request->get('unit', TimeUnitEnum::DAY));    //天或小时
        $from_date = $request->get('from_date', date('Y-m-d', strtotime('-1 month')));  //开始时间
        $to_date = $request->get('to_date', date('Y-m-d')); //结束时间

        // 所有监测点
        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();


        $info['series'] = $info['chartTime'] = $info['legend'] = [];
        // 遍历所有时间点
        for ($i = strtotime($from_date); $i < strtotime($to_date); $i += $unit) {
            array_push($info['chartTime'], date($unit == TimeUnitEnum::getUnit(TimeUnitEnum::HOURS) ? 'm-d H:i' : 'm-d', $i));
        }
        // 遍历所有监测点
        foreach ($pointModel as $value) {
            array_push($info['legend'], $value['title']);
            $data = [];
            for ($i = strtotime($from_date); $i < strtotime($to_date); $i += $unit) {
                $_model = Value::find()
                    ->where(['pid' => $value['id']])
                    ->andWhere(['type' => $type])
                    ->andWhere(['between', 'event_time', $i, $i + $unit])
                    ->andWhere(['state' => ValueStateEnum::ENABLED])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->asArray()
                    ->one();
                array_push($data, $_model['value']);
            }
            array_push($info['series'], [
                'name' => $value['title'],
                'data' => $data,
            ]);
        }

        return $this->render($this->action->id, [
            'id' => $id,
            'info' => $info,
            'from_date' => $from_date,
            'to_date' => $to_date,
        ]);
    }

    /**
     * 导入表格
     * 
     * @return string
     */
    public function actionExcelFile()
    {
        if (Yii::$app->request->isPost) {
            try {
                $file = $_FILES['excelFile'];
                $data = ExcelHelper::import($file['tmp_name'], 2);
                $i = House::addDatas($data);
            } catch (\Exception $e) {
                return $this->message($e->getMessage(), $this->redirect(['index']), 'error');
            }

            return $this->message('导入成功' . $i . '条数据', $this->redirect(['index']));
        }

        return $this->renderAjax($this->action->id);
    }

    /**
     * 下载
     */
    public function actionDownload()
    {
        $file = 'house-default.xls';

        $path = Yii::getAlias('@backend') . '/modules/monitor/file/' . $file;

        Yii::$app->response->sendFile($path, '权限数据_' . time() . '.xls');
    }
}

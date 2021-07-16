<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-10 10:46:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-27 17:14:10
 * @Description: 
 */

namespace api\modules\v1\controllers\project;

use yii;
use common\enums\StatusEnum;
use common\models\monitor\project\Point;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\helpers\ArrayHelper;
use api\controllers\BaseController;
use common\enums\PointEnum;
use common\enums\ValueStateEnum;

/**
 * 房屋控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class PointController extends BaseController
{
    public $modelClass = Point::class;


    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    /**
     * 首页
     *
     * @return ActiveDataProvider
     */
    public function actionIndex($pid, $page = 1, $limit = 20)
    {

        $request = Yii::$app->request;
        $title = $request->get('title' . NULL);
        $query = Point::find()
            ->where(['pid' => $pid])
            ->andWhere(['=', 'status', StatusEnum::ENABLED]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $limit = 20]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach ($model as $key => &$value) {
            $typeModel = PointEnum::getModel($value['type']);
            $data = $typeModel::find()
                ->where(['pid' => $value['id']])
                ->andWhere(['state' => ValueStateEnum::ENABLED])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->orderBy('id desc')
                ->asArray()
                ->one();
            $value['newValue'] = $data ? $data['value'] : 0;
            $prevData = $typeModel::getPrev($data['id']);
            $value['yesterValue'] = $prevData ? $prevData['value'] : 0;
            # code...
        }
        $model = ArrayHelper::index($model, null, 'type');

        $res = [];
        foreach ($model as $key => $value) {
            array_push($res, [
                'key' => $key,
                'title' => PointEnum::getValue($key),
                'name' => PointEnum::getSymbolValue($key),
                'data' => $value,
            ]);
        }


        return $res;
    }

    public function actionView($id)
    {
        $model = Point::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
        $model['type'] = PointEnum::getValue($model['type']);

        return [
            'data' => $model,
            'devices' => Yii::$app->services->point->getDeviceMap($id),
        ];
    }

    /**
     * 近三月数据，按月区分
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */
    public function actionChart($id)
    {
        $model  = $this->findModel($id);

        $info['chartTime'] = $info['data'] = $info['legend'] = [];
        // 时间，X轴
        for ($i = strtotime('-3 month'); $i < time(); $i += 60 * 60 * 24) {
            array_push($info['chartTime'], date('m-d', $i));
        }

        $info['until'] = PointEnum::$Until[$model['type']];   //单位

        // 监测点类型数据
        $startTime = date('Y-m-1');
        $typeModel = PointEnum::getModel($model['type']);
        array_push($info['legend'], '数据');
        $data = [];
        for ($j = strtotime('-3 month', strtotime($startTime)); $j < time(); $j += 60 * 60 * 24) {
            $dataModel = $typeModel::find()
                ->where(['pid' => $id])
                ->andWhere(['between', 'event_time', $j, $j + 60 * 60 * 24])
                ->andWhere(['state' => ValueStateEnum::ENABLED])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->orderBy('value asc')
                ->asArray()
                ->one();
            array_push($data, $dataModel['value'] ?: null);

            
        }
        
        array_push($info['data'], [
            'title' => '数据',
            'data' => $data,
        ]);

        return $info;
    }
}

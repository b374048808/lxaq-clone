<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-10 10:46:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-12 14:11:27
 * @Description: 
 */

namespace api\modules\v1\controllers\project;

use yii;
use api\controllers\OnAuthController;
use common\enums\JudgeEnum;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\enums\PointEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\models\member\HouseMap;
use common\models\monitor\project\House;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\helpers\ArrayHelper;
use common\models\monitor\project\Point;
use common\models\monitor\rule\Child;

/**
 * 房屋控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ValueController extends OnAuthController
{
    public $modelClass = House::class;

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
    public function actionIndex($page = 1, $limit = 20)
    {
        $request = Yii::$app->request;
        $houseId = $request->get('houseId', NULL);
        $pointId = $request->get('pointId', NULL);
        $warn = $request->get('warn', NULL);
        $type = $request->get('type', NULL);
        $todate = $request->get('todate', NULL);
        $todate = $todate?strtotime($todate):null;
        $houseIds = HouseMap::getHouseMap(Yii::$app->user->identity->member_id);
        $where = [];
        if ($houseId && $type) {
            if (!in_array($houseId,$houseIds)) {
                return false;
            }
            $pointModel = Point::find()
                ->where(['pid' => $houseId])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->asArray()
                ->all();
            $ids = ArrayHelper::getColumn($pointModel,'id', $keepKeys = true);
            $modelClass  = PointEnum::getModel($type);
            $where = ['in', 'pid', $ids];
        }elseif ($pointId) {
            $pointModel = Point::findOne($pointId);
            $type = $pointModel->type;
        }else{
            return false;
        }
        $select = [];
        switch ($type) {
            case PointEnum::ANGLE:
                $select = ['id','news','value','event_time','warn','pid'];
                break;
            
            default:
                $select = ['id','value','event_time','warn'];
                break;
        }
        $query = $modelClass::find()    
            ->select($select)   
            ->with(['point'])
            ->where($where)     
            ->andWhere(['>','event_time',strtotime('-3 month')])
            ->andFilterWhere(['between','event_time',$todate,strtotime('+1 day',$todate)])
            ->andFilterWhere(['pid' => $pointId])
            ->andFilterWhere(['warn' => $warn])
            ->andWhere(['type' => $type])
            ->andWhere(['state' => ValueStateEnum::ENABLED])
            ->andWhere(['=', 'status', StatusEnum::ENABLED])
            ->orderBy('event_time desc');
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $limit = 20]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach ($model as $key => &$value) {
            $value['point'] = $value['point']['title'];
            $value['warnText'] = WarnEnum::getValue($value['warn']);
        }
        return [
            'data' => $model,
            'pages' => $pages,
        ];
    }



    public function actionView($id)
    {
        $model = House::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
        // 房屋规则报警值
        $houseItem = [];
        $ruleItem = Child::getChild($id);
        foreach ($ruleItem as $key => $value) {
            if (!empty($value['item'])) {
                foreach ($value['item'] as $key => $value) {
                    array_push($houseItem,[
                        'title' => PointEnum::getValue($value['type']),
                        'warn' => $value['warn'],
                        'judge' => JudgeEnum::getValue($value['judge']).$value['value'],
                    ]);
                }
               
            }
        }
        $model['warns'] = $houseItem;
        return $model;
    }


    /**
     * @param {*} $id
     * @param {*} $type
     * @param {*} $chartType
     * @return array
     * @throws: 
     */
    public function actionChart($id, $type = PointEnum::ANGLE, $chartType = ValueTypeEnum::AUTOMATIC)
    {
        // 遍历所有类型监测
        $model  = Point::find()
            ->where(['pid' => $id])
            ->andWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        // 监测点类型数据
        $typeModel = PointEnum::getModel($type);
        $res['chartTime'] = $res['data'] = $res['legend'] = [];
        // 时间，X轴
        for ($i = strtotime('-3 month'); $i < time(); $i += 60 * 60 * 24) {
            array_push($res['chartTime'], date('m-d', $i));
        }
        $res['until'] = PointEnum::$Until[$type];   //单位
        foreach ($model as $key => &$value) {
            array_push($res['legend'], $value['title']);
            $dataArray = [];
            for ($i = strtotime('-3 month'); $i < time(); $i += 60 * 60 * 24) {
                $data = $typeModel::find()
                    ->where(['pid' => $value['id']])
                    ->andwhere(['type' => $chartType])
                    ->andWhere(['between', 'event_time', $i, $i + 60 * 60 * 24])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['state' => ValueStateEnum::ENABLED])
                    ->orderBy('event_time desc')
                    ->asArray()
                    ->one();

                array_push($dataArray, $data ? $data['value'] : null);
            }
            // 数据
            array_push($res['data'], [
                'title' => $value['title'],
                'data' => $dataArray
            ]);
        }
        // 房屋规则报警值
        $houseItem = [];
        $ruleItem = Child::getChild($id, $type);
        foreach ($ruleItem as $key => $value) {
            if (!empty($value['item'])) {
                $houseItem = array_merge($houseItem, $value['item']);
                # code...
            }
            # code...
        }
        $res['warns'] = ArrayHelper::getColumn($houseItem, 'value');



        return $res;
    }
}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-14 14:57:29
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-26 17:25:03
 * @Description: 
 */

namespace api\modules\v1\controllers\datav;

use Yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\models\member\HouseMap;
use common\models\monitor\project\House;
use common\models\monitor\project\log\WarnLog;
use common\enums\PointEnum;
use common\models\monitor\project\Point;
use yii\data\Pagination;
use common\helpers\ArrayHelper;
/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class DefaultController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        $houseIds = HouseMap::getHouseMap(Yii::$app->user->identity->member_id);

        // 房屋分布图
        $info['map'] = [];

        $model =  House::find()
            ->select(['id', 'title', 'lng', 'lat', 'status'])
            ->with(['warn'])
            ->where(['in', 'id', $houseIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        foreach ($info['map'] as &$value) {
            $value['name'] = $value['title'];
            $value['state'] = $value['warn']['warn'] ?: 1;
            $value['warnText'] = WarnEnum::getValue($value['state']);
        }

        $info['warns'] = [];
        //预警判断，只显示三级预警
        foreach (WarnEnum::getMap() as $key => $value) {
            if ($key < 4) {
                $info['warns'][$key] = [
                    'name'  => $key,
                    'title'  => $value,
                    'value' => 0
                ];
            }
        }

        $info['map'] = [];
        // 遍历点位输出
        foreach ($model as $value) {
            if ($value['lat'] > 0 && $value['lng'] > 0) {
                array_push($info['map'], [
                    'id'    => $value['id'],
                    'name' => $value['title'],
                    'province'  => Yii::$app->services->provinces->getName($value['province_id']),
                    'city'  => Yii::$app->services->provinces->getName($value['city_id']),
                    'county'    => Yii::$app->services->provinces->getName($value['area_id']),
                    'labelOffset' => [0, 0],
                    'lat' => $value['lat'],
                    'lng' => $value['lng'],
                    'state' => $value['warn']['warn'] ?: 0,
                ]);
                if (!empty($value['warn']['warn']) && $value['warn']['warn'] > 0) {
                    $info['warns'][$value['warn']['warn']]['value']++;
                }
            }
        }

        return $info;
    }

    /**
     * 报警列表
     * 
     * @param 
     * @return Array
     * @throws: 
     */
    public function actionList()
    {
        $pointIds = HouseMap::getPointColumn(Yii::$app->user->identity->member_id);

        $info['warnCount'] = WarnLog::find()
            ->where(['in', 'pid', $pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->count();

        $info['list'] = [];
        $model = WarnLog::find()
            ->with('house')
            ->where(['in', 'pid', $pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($model as $key => $value) {
            array_push($info['list'], [
                'id' => $value['house']['id'],
                'title' => $value['house']['title'],
                'date' => date('m-d H:i', $value['created_at']),
                'hot'  => (int)$value['warn'],
            ]);
        }
        return $info;
    }

    public function actionHouseList()
    {
        $pointIds = HouseMap::getPointColumn(Yii::$app->user->identity->member_id);
        $request = Yii::$app->request;
        $warn = $request->get('warn',null);


        $pointModel = Point::find()
            ->where(['in','id',$pointIds])
            ->andFilterWhere(['warn' => $warn])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        $houseIds = ArrayHelper::getColumn($pointModel,'pid', $keepKeys = true);
        $query = House::find()
            ->select([
                'title', 'id', 'cover',
                'address', 'status', 'lng', 'lat'
            ])
            ->with(['warn', 'point' => function ($queue) {
                $queue->groupBy('type')->select(['title', 'type' ,'pid']);
            }])
            ->andWhere(['in', 'id', $houseIds])
            ->andWhere(['=', 'status', StatusEnum::ENABLED]);
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $limit = 20]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();

        return [
            'data' => $model,
            'pages' => $pages,
        ];

    }

    /**
     * 图表信息  各类占比|历史报警|预警趋势
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionChart()
    {

        $pointIds = HouseMap::getPointColumn(Yii::$app->user->identity->member_id);
        // 监测点分类占比
        $info['pointType'] = [];
        foreach (PointEnum::getMap() as $key => $value) {
            array_push($info['pointType'], [
                'value' => (int)Point::find()
                    ->where(['in', 'pid', $pointIds])
                    ->andWhere(['type' => $key])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->count(),
                'name' => $value,
            ]);
        }


        // 	本周与上周对比预警数量

        //获取今天是周几，0为周日
        $this_week_num = date('w');

        $timestamp = time();
        //如果获取到的日期是周日，需要把时间戳换成上一周的时间戳
        //英语国家 一周的开始时间是周日
        if ($this_week_num == 0) {
            $timestamp = $timestamp - 86400;
        }

        $this_week_arr =  [
            [
                'week_name' => '星期一',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Monday", $timestamp))),
            ],
            [
                'week_name' => '星期二',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Tuesday", $timestamp))),
            ],
            [
                'week_name' => '星期三',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Wednesday", $timestamp))),
            ],
            [
                'week_name' => '星期四',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Thursday", $timestamp))),
            ],
            [
                'week_name' => '星期五',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Friday", $timestamp))),
            ],
            [
                'week_name' => '星期六',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Saturday", $timestamp))),
            ],
            [
                'week_name' => '星期天',
                'week_time' => strtotime(date('Y-m-d', strtotime("this week Sunday", $timestamp))),
            ],
        ];
        $warnNum = [];
        $warnNum['timelineData'] = ['本周', '上周'];
        foreach ($this_week_arr as $key => $value) {
            $warnNum['pList'][] = $value['week_name'];
            $warnNum['dataType'][0][] = WarnLog::find()
                ->where(['in', 'pid', $pointIds])
                ->andWhere(['=', 'status', StatusEnum::ENABLED])
                ->andWhere(['>', 'warn', WarnEnum::SUCCESS])
                ->andWhere(['between', 'created_at', $value['week_time'], $value['week_time'] + 86400])
                ->count();
            $warnNum['dataType'][1][] = WarnLog::find()
                ->where(['in', 'pid', $pointIds])
                ->andWhere(['=', 'status', StatusEnum::ENABLED])
                ->andWhere(['>', 'warn', WarnEnum::SUCCESS])
                ->andWhere(['between', 'created_at', $value['week_time'] - 7 * 86400, $value['week_time'] - 6 * 86400])
                ->count();
        }
        $info['week_count'] = $warnNum;


         // 前6个月报警趋势
         $info['monthWarns'] = [];
         for ($i = 6; $i > 0; $i--) {
             $startTime = strtotime(-$i . 'month');
             $info['monthWarns']['num'][] = WarnLog::find()
                 ->where(['in', 'pid', $pointIds])
                 ->andWhere(['=', 'status', StatusEnum::ENABLED])
                 ->andWhere(['>', 'warn', WarnEnum::SUCCESS])
                 ->andWhere(['between', 'created_at', $startTime, strtotime(date('y-m-d', $startTime) . '+1 month')])
                 ->count();
            $info['monthWarns']['time'][] = (int)date('m', $startTime) . '月';
         }
 
        

        return $info;
    }
}

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-14 14:18:55
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-27 10:42:18
 * @Description: 
 */

namespace api\modules\v1\controllers\home;

use yii;
use api\controllers\OnAuthController;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\models\monitor\project\log\WarnLog;
use common\models\member\HouseMap;
use common\models\monitor\project\House;
use common\models\monitor\project\Point;
use Swoole\Http\Status;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class SiteController extends OnAuthController
{
    public $modelClass = WarnLog::class;


    public function actionIndex()
    {

        $houseIds = HouseMap::getHouseMap(Yii::$app->user->identity->member_id);
        $pointIds = HouseMap::getPointColumn(Yii::$app->user->identity->member_id);
        // 初步数据统计
        $info['panelData'] = [
            [
                'icon' => 'example',
                'title' => '监测建筑',
                'name' => 'house',
                'value' => House::find()
                    ->andWhere(['in', 'id', $houseIds])
                    ->count(),
                'count' => House::find()
                    ->andWhere(['in', 'id', $houseIds])
                    ->count(),
                'color' => '#36a3f7'
            ],
            [
                'icon' => 'warning',
                'title' => '报警',
                'name' => 'warning',
                'value' => WarnLog::find()
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['in', 'pid', $pointIds])
                    ->count(),
                'count' => WarnLog::find()
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['in', 'pid', $pointIds])
                    ->count(),
                'color' => '#f4516c'
            ],
            [
                'icon' => 'point',
                'title' => '动态点位',
                'name' => 'device',
                'value' => 0,
                'count' => Point::find()
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->andWhere(['in', 'pid', $pointIds])
                    ->count(),
                'color' => '#40c9c6'
            ],
        ];

        $info['lineData'] = [];
        // 近3月，报警曲线图
        $start_time = strtotime('-3 month');
        $end_time = time();
        $info['lineData']['time'] = $info['lineData']['data'] = [];
        for ($i=$start_time; $i <$end_time ; $i+= 60*60*24) { 

            array_push($info['lineData']['time'],date('m-d',$i));   //X轴时间
            array_push($info['lineData']['data'],
                WarnLog::find()
                    ->where(['status' => StatusEnum::ENABLED])
                    ->andWhere(['in','pid',$pointIds])
                    ->andWhere(['between','created_at',$i,$i+60*60*24])
                    ->count()
            );   //X轴时间
            # code...
        }

        // 各类型监测点
        $info['pieChart'] = [];
        foreach (PointEnum::getMap() as $key => $value) {
            array_push($info['pieChart'],[
                'name'=> $value,
                'value' => Point::find()
                    ->where(['type' => $key])
                    ->andWhere(['in','id',$pointIds])
                    ->andWhere(['status' => StatusEnum::ENABLED])
                    ->count()
            ]);
            # code...
        }
        return $info;
    }
}

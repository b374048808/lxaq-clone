<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:42:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-29 15:27:13
 * @Description: 
 */

namespace datav\modules\v1\controllers\project;

use yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use common\models\monitor\project\point\Value;
use common\models\monitor\project\point\Warn;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class WarnController extends OnAuthController
{
    public $modelClass = Value::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index','chart'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    { 
        // 返回数组
        $info = [];
        $model = Warn::find()
            ->with(['house','point'])
            ->where(['state' => WarnStateEnum::AUDIT])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($model as $key => $value) {
            array_push($info,[
                'house_id' => $value['house']['id'],
                'house_title' => $value['house']['title'],
                'point_id' => $value['point']['id'],
                'point_title' => $value['point']['title'],
                'warn' => $value['warn'],
                'warn_text' => WarnEnum::getValue($value['warn']),
                'time' => date('m-d H:i',$value['created_at']),
                'state_text' => WarnStateEnum::getValue($value['state'])
            ]);
        }
        return $info;
    }


    public function actionChart(){
        $info = [];
        $months = [];
        $num = [];
        for ($i=6; $i > 0; $i--) { 
            $month = '-'.$i.' month';
            $start_time = strtotime($month,strtotime(date('Y-m-1 00:00:00')));
            $end_time = strtotime('+1 month',$start_time);
            $model =  Warn::find()
                ->where(['state' => WarnStateEnum::AUDIT])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->andWhere(['between','created_at',$start_time,$end_time])
                ->count();
            array_push($months,date('n',$start_time).'月');
            array_push($num,$model);
        }
        return [
            'month' => $months,
            'num'   => $num
        ];
    }
}

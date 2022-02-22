<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:42:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-29 17:02:57
 * @Description: 
 */

namespace datav\modules\v1\controllers\project;

use yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Value;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class PointController extends OnAuthController
{
    public $modelClass = Point::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index', 'chart', 'area-map','view'];


    public function actionView($id){
        $model = Point::find()
            ->with('point')
            ->where(['id' => $id])
            ->asArray()
            ->one();
        return $model;
    }

    public function actionChart($id){
        $start_time = strtotime('-3 month');
        $model = Value::find()
            ->where(['pid' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['state' => ValueStateEnum::ENABLED])
            ->andWhere(['between','event_time',$start_time,time()])
            ->orderBy('event_time asc')
            ->asArray()
            ->all();
        $info = [];
        foreach ($model as $key => $value) {
            array_push($info,[
                date('n-d H:i',$value['event_time']),
                $value['value'],
            ]);
            # code...
        }
        return $info;

    }
}

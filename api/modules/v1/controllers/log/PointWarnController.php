<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-14 14:18:55
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-14 14:52:32
 * @Description: 
 */

namespace api\modules\v1\controllers\log;

use yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use common\models\member\HouseMap;
use common\models\monitor\project\log\WarnLog;
use common\models\monitor\project\Point;
use yii\data\Pagination;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class PointWarnController extends OnAuthController
{
    public $modelClass = WarnLog::class;

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
    public function actionIndex($page = 1, $limit = 20)
    { 
        // 查询当前用户所有关联的房屋
        $houseIds = HouseMap::getHouseMap(Yii::$app->user->identity->member_id);
        // 客户端时间范围查询数据
        $request = Yii::$app->request;
        $time = $request->get('time');
        $start_time = $time[0]?strtotime($time[0]):null;
        $end_time = $time[1]?strtotime($time[1]):null;
               
        $pointIds = [];
        foreach ($houseIds as $value) {
            $pointIds = array_merge($pointIds,Point::getColumn($value));
        }

        $query = WarnLog::find()
            ->select(['pid','warn','state','created_at','remark'])
            ->with(['house' => function($queue){
                $queue->select(['id','title']);
            },'point'=> function($queue){
                $queue->select(['id','title']);
            }])
            ->where(['in', 'pid', $pointIds])
            ->andFilterWhere(['between','created_at',$start_time, $end_time])
            ->andWhere(['status' => StatusEnum::ENABLED]);
            
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $limit = 20]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        
        foreach ($model as  &$value) {
            $value['warnText'] = WarnEnum::getValue($value['warn']);
            $value['state'] = WarnStateEnum::getValue($value['state']);
        }
        
        
        return [
            'data' => $model,
            'pages' => $pages,
        ];

    }
}

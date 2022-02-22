<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-10 10:46:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-21 17:29:22
 * @Description: 
 */

namespace api\modules\v1\controllers\project;

use yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\monitor\project\point\Warn;
use common\models\member\HouseMap;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;

/**
 * 报警记录控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class WarnController extends OnAuthController
{
    public $modelClass = Warn::class;

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
    public function actionIndex($page = 1, $limit = 20){

        $request = Yii::$app->request;
        $pid = $request->get('house',null);
        $warn = $request->get('warn',null);
        $todate = $request->get('todate', NULL);    //时间
        $todate = $todate ? strtotime($todate) : null;
        $time = $request->get('time', NULL);    //时间
        // 报警列表时间搜索
        $where = [];
        if ($time) {
            $where = ['between','created_at',strtotime($time[0]),strtotime('+1 day',strtotime($time[1]))];
        }
        $pointIds = HouseMap::getPointColumn(Yii::$app->user->identity->member_id,$pid);


        $query = $this->modelClass::find()
            ->with(['point','house' => function($queue){
                $queue->select(['id','title']);
            }])
            ->where(['in','pid',$pointIds])
            ->andWhere($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['between', 'created_at', $todate, strtotime('+1 day', $todate)])
            ->andFilterWhere(['warn' => $warn])
            ->orderBy('id desc');
        // 分页
        $pages = new Pagination(['totalCount' => $query->count(), 'pageSize' => $limit = 20]);
        $model = $query->offset($pages->offset)->limit($pages->limit)->asArray()->all();
        foreach ($model as $key => &$value) {
            $value['point'] = $value['point']['title'];
            $value['warnState'] = WarnStateEnum::getValue($value['state']);
            $value['warnText'] = WarnEnum::getValue($value['warn']);
        }
         return [
            'data' => $model,
            'pages' => $pages,
        ];
    }
}
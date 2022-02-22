<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-19 10:43:29
 * @Description: 
 */

namespace workapi\modules\v1\controllers\member;

use Yii;
use yii\web\NotFoundHttpException;
use workapi\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\worker\Worker as Member;
use common\helpers\ArrayHelper;
use common\models\worker\Notify;
use common\models\worker\NotifyMember;

/**
 * 会员接口
 *
 * Class WorkerController
 * @package workapi\modules\v1\controllers\worker
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends OnAuthController
{
    /**
     * @var Worker
     */
    public $modelClass = Member::class;

    /**
     * 单个显示
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = $this->modelClass::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->select([
                'id', 'username', 'nickname',
                'realname', 'head_portrait', 'gender',
                'qq', 'email', 'birthday',
                'status','mobile',
                'created_at'
            ])
            ->asArray()
            ->one();

        return $model;
    }

    /**
     * 员工列表
     *
     * @return array
     * @throws NotFoundHttpException
     */
    public function actionList(){
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);


        $model = Member::find()
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like','realname',$title])
            ->asArray()
            ->all();

        return ArrayHelper::map($model, 'id','realname');
    }

    public function actionIndex($start = 0, $limit = 10){
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);
        
        $model = Member::find()
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like','realname',$title])
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();
        return $model;
    }

    public function actionDefault(){
        $member_id = Yii::$app->user->identity->member_id;

        $messageCount = NotifyMember::find()
            ->where(['type' => Notify::TYPE_MESSAGE])
            ->andWhere(['member_id' => $member_id])
            ->andWhere(['is_read' => 0])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->count();
        return [
            'message_count' => $messageCount
        ];
        
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'view'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}

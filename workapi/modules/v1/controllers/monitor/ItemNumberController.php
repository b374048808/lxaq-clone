<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-01-06 09:52:04
 * @Description: 
 */

namespace workapi\modules\v1\controllers\monitor;

use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use workapi\controllers\OnAuthController;
use common\models\monitor\project\Item;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ItemNumberController extends OnAuthController
{
    public $modelClass = Item::class;


    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['rbac'];

    /**
     * 所有
     * 
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);      //名称
        $number = $request->get('number', false);

        $where = $number ? ['number' => ''] : [];

        $model = Item::find()
            ->where($where)
            ->select(['id', 'user_id', 'title', 'steps', 'status', 'audit', 'start_time', 'end_time', 'number', 'created_at'])
            ->with(['user'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['audit' => VerifyEnum::PASS])
            ->andFilterWhere(['or', ['like', 'title', $title], ['like', 'number', $title]])
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();


        return $model;
    }

    //发送摇号订阅消息
    public function actionSignature()
    {
        $request = Yii::$app->request;
        $message_data =  $request->post('message_data');
        Yii::$app->services->workerMiniMessage->createRemind($message_data['openid'], '', MessageActionEnum::NUMBER_REMIND, MessageReasonEnum::ITEM_VERIFY);
        return true;
    }
}

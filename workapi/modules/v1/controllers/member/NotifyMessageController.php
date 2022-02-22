<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-19 11:55:36
 * @Description: 
 */

namespace workapi\modules\v1\controllers\member;

use Yii;
use yii\web\NotFoundHttpException;
use workapi\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\worker\Notify;
use workapi\modules\v1\forms\NotifyMessageForm;
use common\models\worker\NotifyMember;
/**
 * 会员接口
 *
 * Class WorkerController
 * @package workapi\modules\v1\controllers\worker
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyMessageController extends OnAuthController
{
    /**
     * @var Worker
     */
    public $modelClass = NotifyMember::class;

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

    public function actionList($start = 0, $limit = 20){

        $model = NotifyMember::find()
            ->with(['notifySenderForMember', 'notify'])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andWhere(['type' => Notify::TYPE_MESSAGE, 'member_id' => Yii::$app->user->identity->member_id])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();

        $ids = [];
        foreach ($model as $datum) {
            $datum['is_read'] == 0 && $ids[] = $datum['notify_id'];
        }

         // 设置消息为已读
        !empty($ids) && Yii::$app->services->workerNotify->read(Yii::$app->user->identity->member_id, $ids);

        return $model;
    }

    public function actionEdit(){
        $model = new NotifyMessageForm();
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post(),'') && Yii::$app->services->workerNotify->createMessage($model->content, Yii::$app->user->identity->member_id, $model->toManagerId)) {
            return true;
        }
        throw new NotFoundHttpException('发送失败!');
        

    }

    public function actionRealAll(){
        NotifyMember::updateAll(['is_read' => true, 'updated_at' => time()], ['member_id' => Yii::$app->user->identity->member_id,'type' => Notify::TYPE_MESSAGE, 'is_read' => false]);
        // 数据为0时反回false报错，直接返回true
        return  true;
    }

    /**
     * 删除
     *
     * @param $id
     * @return bool
     * @throws NotFoundHttpException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::DELETE;

        if($model->save()){
            $notifyModel = Notify::findOne($model->notify_id);
            $notifyModel->target_display = StatusEnum::DELETE;
            $notifyModel->save();
            return true;
        }
        return false;
    }
}

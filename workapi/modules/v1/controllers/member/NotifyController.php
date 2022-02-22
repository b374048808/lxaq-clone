<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 16:06:19
 * @Description: 
 */

namespace workapi\modules\v1\controllers\member;

use common\enums\StatusEnum;
use Yii;
use yii\web\NotFoundHttpException;
use workapi\controllers\OnAuthController;
use common\models\worker\Notify;
use common\models\worker\NotifyMember;
use workapi\modules\v1\forms\NotifyMessageForm;
/**
 * 会员接口
 *
 * Class WorkerController
 * @package workapi\modules\v1\controllers\worker
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyController extends OnAuthController
{
    /**
     * @var Worker
     */
    public $modelClass = Notify::class;

    /**
     * 单个显示
     *
     * @param $id
     * @return mixed
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {

        $model = NotifyMember::find()
            ->with(['notify','notifySenderForMember'])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        
        NotifyMember::updateAll(['is_read' => true, 'updated_at' => time()], ['id' => $model['id']]);
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

    public function actionDeleteAll(){
        $request =Yii::$app->request;
        $ids = $request->post('ids',[]);
        return NotifyMember::updateAll(['status' => StatusEnum::DELETE],['in','id',$ids]);

    }
}

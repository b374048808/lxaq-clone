<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-11 09:56:25
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-28 16:19:19
 * @Description: 
 */

namespace services\monitor;

use Yii;
use common\components\Service;
use common\models\monitor\project\item\AuditLog;
use common\models\monitor\project\item\VerifyLog;
use common\enums\VerifyEnum;
use common\models\monitor\project\item\StepsLog;
use common\models\worker\Worker;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class ItemService extends Service
{
    public function addVerifyLog($id,$audit = true,$desc = ''){
        $member_id = Yii::$app->user->identity->member_id;
        $verifyModel = new VerifyLog();
        $verifyModel->member_id = $member_id;
        $verifyModel->map_id = $id;
        $verifyModel->ip = Yii::$app->request->userIP;
        $verifyModel->verify = $audit;
        $verifyModel->remark = Worker::getRealname($member_id) . VerifyEnum::getAudit($audit);
        $verifyModel->description = $desc;
        return $verifyModel->save();
    }

    public function addStepsLog($id,$audit = true,$desc = ''){
        
        $member_id = Yii::$app->user->identity->member_id;
        $model =new StepsLog();
        $model->member_id = $member_id;
        $model->pid = $id;
        $model->ip = Yii::$app->request->userIP;
        $model->verify = $audit ? 1 : 0;
        $str = $audit ? '确认下一步' : '返回上一步';
        $model->remark = Worker::getRealname($member_id) . $str;
        $model->description = $desc;
        return $model->save();

    }
}

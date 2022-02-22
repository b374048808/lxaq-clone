<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-11 09:56:25
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-29 09:31:36
 * @Description: 
 */

namespace services\project;

use Yii;
use common\components\Service;
use common\models\monitor\project\house\ReportVerify;
use common\enums\VerifyEnum;
use common\models\worker\Worker;

/**
 * Class MemberService
 * @package services\member
 * @author jianyan74 <751393839@qq.com>
 */
class HouseReportService extends Service
{
    public function addVerifyLog($id,$audit,$desc = ''){
        $member_id = Yii::$app->user->identity->member_id;
        $verifyModel = new ReportVerify();
        $verifyModel->member_id = $member_id;
        $verifyModel->pid = $id;
        $verifyModel->ip = Yii::$app->request->userIP;
        $verifyModel->verify = $audit;
        $verifyModel->remark = Worker::getRealname($member_id) . VerifyEnum::getAudit($audit);
        $verifyModel->description = $desc;
        return $verifyModel->save();
    }

}

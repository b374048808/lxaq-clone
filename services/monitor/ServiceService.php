<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-22 11:02:52
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-28 16:13:59
 * @Description: 
 */

namespace services\monitor;

use Yii;
use yii\data\Pagination;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\monitor\project\service\Audit;
use common\models\monitor\project\service\Map;
use common\enums\VerifyEnum;
use common\models\monitor\project\service\ServiceMember;
use common\models\worker\Worker;

/**
 * Class NotifyService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceService extends Service
{
    /**
     * 创建一条提醒
     *
     * @param int $sender_id 触发id
     * @param string $content 内容
     * @param int $receiver 接收id
     */
    public function createMessage($service_id, $manager)
    {
        $serviceMenber = new ServiceMember();
        $serviceMenber->service_id = $service_id;
        $serviceMenber->member_id = $manager;
        return $serviceMenber->save();

    }

    /**
     * 获取用户消息列表
     *
     * @param $member_id
     */
    public function getUserService($member_id, $is_read = 0)
    {
        $data = ServiceMember::find()
            ->where(['status' => StatusEnum::ENABLED, 'is_read' => $is_read])
            ->andWhere(['member_id' => $member_id]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => 10]);
        $models = $data->offset($pages->offset)
            ->with('service')
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->asArray()
            ->all();

        return [$models, $pages];
    }


    public function getRead($member_id)
    {
        return  ServiceMember::find()
            ->where(['status' => StatusEnum::ENABLED,'is_read' => false])
            ->andWhere(['member_id' => $member_id])
            ->exists();
    }

    public function getHouseCount($id,$audit = false)
    {
        $where = [];
        if ($audit) {
            $where = ['not',['report_id' => null]];
        }
        return Map::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['pid' => $id])
            ->andWhere($where)
            ->count();

    }

    /**
     * 更新指定的notify，把isRead属性设置为true
     *
     * @param $member_id
     */
    public function read($member_id, $serviceIds)
    {
        ServiceMember::updateAll(['is_read' => true, 'updated_at' => time()], ['and', ['member_id' => $member_id], ['in', 'service_id', $serviceIds]]);
    }

    /**
     * 全部设为已读
     *
     * @param $member_id
     */
    public function readAll($member_id)
    {
        ServiceMember::updateAll(['is_read' => true, 'updated_at' => time()], ['member_id' => $member_id, 'is_read' => false]);
    }
    
    public function addVerifyLog($id,$audit,$desc = ''){
        $member_id = Yii::$app->user->identity->member_id;
        $verifyModel = new Audit();
        $verifyModel->user_id = $member_id;
        $verifyModel->pid = $id;
        $verifyModel->ip = Yii::$app->request->userIP;
        $verifyModel->verify = $audit;
        $verifyModel->remark = Worker::getRealname($member_id) . VerifyEnum::getAudit($audit);
        $verifyModel->description = $desc;
        return $verifyModel->save();
    }
}
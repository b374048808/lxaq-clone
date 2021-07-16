<?php

namespace services\backend;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\worker\Worker;
use common\components\Service;

/**
 * Class MemberService
 * @package services\backend
 * @author jianyan74 <751393839@qq.com>
 */
class WorkerService extends Service
{
    /**
     * 记录访问次数
     *
     * @param Member $member
     */
    public function lastLogin(Worker $worker)
    {
        $worker->visit_count += 1;
        $worker->last_time = time();
        $worker->last_ip = Yii::$app->request->getUserIP();
        $worker->save();
    }

    /**
     * @return array
     */
    public function getMap()
    {
        return ArrayHelper::map($this->findAll(), 'id', 'username');
    }

    /**
     * @return array|\yii\db\ActiveRecord[]
     */
    public function findAll()
    {
        return Worker::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findById($id)
    {
        return Worker::find()
            ->where(['id' => $id, 'status' => StatusEnum::ENABLED])
            ->one();
    }

    /**
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByIdWithAssignment($id)
    {
        return Worker::find()
            ->where(['id' => $id])
            ->with('assignment')
            ->one();
    }
}
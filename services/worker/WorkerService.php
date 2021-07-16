<?php

namespace services\worker;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\worker\Worker;
use common\helpers\EchantsHelper;
use common\helpers\TreeHelper;

/**
 * Class WorkerService
 * @package services\worker
 * @author jianyan74 <751393839@qq.com>
 */
class WorkerService extends Service
{
    /**
     * 用户
     *
     * @var \common\models\worker\Worker
     */
    protected $worker;

    /**
     * @param Worker $worker
     * @return $this
     */
    public function set(Worker $worker)
    {
        $this->worker = $worker;
        return $this;
    }

    /**
     * @param $id
     * @return array|Worker|\yii\db\ActiveRecord|null
     */
    public function get($id)
    {
        if (!$this->worker || $this->worker['id'] != $id) {
            $this->worker = $this->findById($id);
        }

        return $this->worker;
    }

    /**
     * 获取区间会员数量
     *
     * @return array|\yii\db\ActiveRecord|null
     */
    public function getBetweenCountStat($type)
    {
        $fields = [
            'count' => '注册会员人数',
        ];

        // 获取时间和格式化
        list($time, $format) = EchantsHelper::getFormatTime($type);
        // 获取数据
        return EchantsHelper::lineOrBarInTime(function ($start_time, $end_time, $formatting) {
            return Worker::find()
                ->select(['count(id) as count', "from_unixtime(created_at, '$formatting') as time"])
                ->where(['>', 'status', StatusEnum::DISABLED])
                ->andWhere(['between', 'created_at', $start_time, $end_time])
                ->groupBy(['time'])
                ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
                ->asArray()
                ->all();
        }, $fields, $time, $format);
    }

    /**
     * @param $level
     * @return array|\yii\db\ActiveRecord|null
     */
    public function hasLevel($level)
    {
        return Worker::find()
            ->where(['current_level' => $level])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * 获取所有下级id
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getChildIdsById($id)
    {
        $worker = $this->get($id);

        return Worker::find()
            ->select(['id'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['like', 'tree', $worker->tree . TreeHelper::prefixTreeKey($worker->id) . '%', false])
            ->andWhere(['<', 'level', $worker->level + 3])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->asArray()
            ->column();
    }

    /**
     * 获取下一级用户id
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord[]
     */
    public function getNextChildIdsById($id)
    {
        $worker = $this->get($id);

        return Worker::find()
            ->select(['id'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['like', 'tree', $worker->tree . TreeHelper::prefixTreeKey($worker->id) . '%', false])
            ->andWhere(['level' => $worker->level + 1])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->orderBy('id desc')
            ->asArray()
            ->column();
    }

    /**
     * 根据推广码查询
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByPromoCode($promo_code)
    {
        return Worker::find()
            ->where(['promo_code' => $promo_code, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * 根据手机号码查询
     *
     * @param $id
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByMobile($mobile)
    {
        return Worker::find()
            ->where(['mobile' => $mobile, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $condition
     * @return array|\yii\db\ActiveRecord|null
     */
    public function findByCondition(array $condition)
    {
        return Worker::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere($condition)
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
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
     * @param Worker $worker
     */
    public function lastLogin(Worker $worker)
    {
        // 记录访问次数
        $worker->visit_count += 1;
        $worker->last_time = time();
        $worker->last_ip = Yii::$app->request->getUserIP();
        $worker->save();
    }
}
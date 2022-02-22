<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 16:49:12
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 16:50:56
 * @Description: 
 */

namespace common\queues;

use Yii;
use yii\base\BaseObject;

/**
 * Class SmsJob
 * @package common\queues
 * @author jianyan74 <751393839@qq.com>
 */
class VerifySmsJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $manager;

    /**
     * @var
     */
    public $item;

    /**
     * @var
     */
    public $usage;

    /**
     * @var
     */
    public $member_id;

    /**
     * @var
     */
    public $ip;

    /**
     * @param \yii\queue\Queue $queue
     * @return mixed|void
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function execute($queue)
    {
        Yii::$app->services->workerVerifySms->realSend($this->mobile, $this->manager, $this->item, $this->usage, $this->member_id, $this->ip);
    }
}
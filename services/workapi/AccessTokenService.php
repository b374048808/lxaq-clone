<?php

namespace services\workapi;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UnprocessableEntityHttpException;
use common\enums\CacheEnum;
use common\helpers\ArrayHelper;
use common\models\worker\Worker;
use common\models\workapi\AccessToken;
use common\components\Service;
use common\enums\StatusEnum;

/**
 * Class AccessTokenService
 * @package services\workapi
 * @author jianyan74 <751393839@qq.com>
 */
class AccessTokenService extends Service
{
    /**
     * 是否加入缓存
     *
     * @var bool
     */
    public $cache = false;

    /**
     * 缓存过期时间
     *
     * @var int
     */
    public $timeout;

    /**
     * 获取token
     *
     * @param Worker $worker
     * @param $group
     * @param int $cycle_index 重新获取次数
     * @return array
     * @throws \yii\base\Exception
     */
    public function getAccessToken(Worker $worker, $group, $cycle_index = 1)
    {
        $model = $this->findModel($worker->id, $group);
        $model->worker_id = $worker->id;
        $model->group = $group;
        // 删除缓存
        !empty($model->access_token) && Yii::$app->cache->delete($this->getCacheKey($model->access_token));
        $model->refresh_token = Yii::$app->security->generateRandomString() . '_' . time();
        $model->access_token = Yii::$app->security->generateRandomString() . '_' . time();
        $model->status = StatusEnum::ENABLED;

        if (!$model->save()) {
            if ($cycle_index <= 3) {
                $cycle_index++;
                return self::getAccessToken($worker, $group, $cycle_index);
            }

            throw new UnprocessableEntityHttpException($this->getError($model));
        }

        $result = [];
        $result['refresh_token'] = $model->refresh_token;
        $result['access_token'] = $model->access_token;
        $result['expiration_time'] = Yii::$app->params['user.accessTokenExpire'];

        // 记录访问次数
        Yii::$app->services->worker->lastLogin($worker);

        $worker = ArrayHelper::toArray($worker);
        unset($worker['password_hash'], $worker['auth_key'], $worker['password_reset_token'], $worker['access_token'], $worker['refresh_token']);
        $result['member'] = $worker;

        // 写入缓存
        $this->cache === true && Yii::$app->cache->set($this->getCacheKey($model->access_token), $model, $this->timeout);

        return $result;
    }

    /**
     * @param $token
     * @param $type
     * @return array|mixed|null|ActiveRecord
     */
    public function getTokenToCache($token, $type)
    {
        if ($this->cache == false) {
            return $this->findByAccessToken($token);
        }

        $key = $this->getCacheKey($token);
        if (!($model = Yii::$app->cache->get($key))) {
            $model = $this->findByAccessToken($token);
            Yii::$app->cache->set($key, $model, $this->timeout);
        }

        return $model;
    }

    /**
     * 禁用token
     *
     * @param $access_token
     */
    public function disableByAccessToken($access_token)
    {
        $this->cache === true && Yii::$app->cache->delete($this->getCacheKey($access_token));

        if ($model = $this->findByAccessToken($access_token)) {
            $model->status = StatusEnum::DISABLED;
            return $model->save();
        }

        return false;
    }

    /**
     * 获取token
     *
     * @param $token
     * @return array|null|ActiveRecord|AccessToken
     */
    public function findByAccessToken($token)
    {
        return AccessToken::find()
            ->where(['access_token' => $token, 'status' => StatusEnum::ENABLED])
            ->andFilterWhere(['merchant_id' => $this->getMerchantId()])
            ->one();
    }

    /**
     * @param $access_token
     * @return string
     */
    protected function getCacheKey($access_token)
    {
        return CacheEnum::getPrefix('workapiAccessToken') . $access_token;
    }

    /**
     * 返回模型
     *
     * @param $worker_id
     * @param $group
     * @return array|AccessToken|null|ActiveRecord
     */
    protected function findModel($worker_id, $group)
    {
        if (empty(($model = AccessToken::find()->where([
            'worker_id' => $worker_id,
            'group' => $group
        ])->andFilterWhere(['merchant_id' => $this->getMerchantId()])->one()))) {
            $model = new AccessToken();
            return $model->loadDefaultValues();
        }

        return $model;
    }
}
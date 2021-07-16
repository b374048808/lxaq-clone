<?php

namespace workapi\modules\v1\controllers\member;

use Yii;
use yii\web\NotFoundHttpException;
use workapi\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\worker\Worker as Member;
use common\helpers\WorkerAuth as Auth;

/**
 * 会员接口
 *
 * Class WorkerController
 * @package workapi\modules\v1\controllers\worker
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class MemberController extends OnAuthController
{
    /**
     * @var Worker
     */
    public $modelClass = Member::class;

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
                'status',
                'created_at'
            ])
            ->asArray()
            ->one();

        return $model;
    }

    /**
     * 权限验证
     *
     * @param string $action 当前的方法
     * @param null $model 当前的模型类
     * @param array $params $_GET变量
     * @throws \yii\web\BadRequestHttpException
     */
    public function checkAccess($action, $model = null, $params = [])
    {
        // 方法名称
        if (in_array($action, ['delete', 'view'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}

<?php

namespace merapi\modules\v1\controllers;

use Yii;
use merapi\controllers\OnAuthController;
use common\helpers\WorkerAuth;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package merapi\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class DefaultController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return 'index';
    }

    /**
     * 测试查询方法
     *
     * 注意：该方法在 main.php 文件里面的 extraPatterns 单独配置过才正常访问
     *
     * @return string
     */
    public function actionSearch()
    {
        $permissionName = '/' . Yii::$app->controller->route;
         // 判断是否忽略校验
       
         // 开始权限校验
         return WorkerAuth::verify($permissionName);
         if (!WorkerAuth::verify($permissionName)) {
             throw new \yii\web\BadRequestHttpException('对不起，您现在还没获此操作的权限');
         }

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
        if (in_array($action, ['search', 'index'])) {
            throw new \yii\web\BadRequestHttpException('权限不足');
        }
    }
}

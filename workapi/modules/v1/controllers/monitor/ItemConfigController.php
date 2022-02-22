<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-28 14:11:24
 * @Description: 
 */

namespace workapi\modules\v1\controllers\monitor;

use workapi\controllers\OnAuthController;
use common\models\monitor\project\item\Config;
use yii\web\NotFoundHttpException;
use common\enums\PointEnum;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ItemConfigController extends OnAuthController
{
    public $modelClass = Config::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];    

    /**
     * 修改项目配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);
        $model = Config::findOne(['pid' => $pid]) ? Config::findOne(['pid' => $pid]) : new Config();

        if ($model->load($request->post(), '')) {
            if ($model->save())
                return true;     
        }
        throw new NotFoundHttpException($this->getError($model));
    }

    /**
     * 默认配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public function actionDefault(){
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);

        $itemModel = new Config();
        $model = Config::findOne(['pid' => $pid])?:$itemModel->loadDefaultValues();
        $model->pid = $pid?:$model->pid;
        return [
            'model' => $model,
            'type_enum'  => PointEnum::getMap(),
        ];
    }

}

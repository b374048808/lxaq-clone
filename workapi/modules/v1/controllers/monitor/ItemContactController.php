<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-08 16:05:34
 * @Description: 
 */

namespace workapi\modules\v1\controllers\monitor;

use workapi\controllers\OnAuthController;
use common\models\monitor\project\Item;
use common\models\monitor\project\item\Contract;
use common\models\monitor\project\log\ItemAudit;
use common\models\worker\Worker;
use yii\web\NotFoundHttpException;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ItemContactController extends OnAuthController
{
    public $modelClass = Contract::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];


    /**
     * 项目合同提交
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public function actionEdit(){
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $model = $id ? $this->findModel($id) : new Contract();

        if ($model->load($request->post('data'), '')) {
            $model->user_id = Yii::$app->user->identity->member_id;
            if (!$model->save()) {
                throw new NotFoundHttpException($this->getError($model));
            }else{
                return true;
            }
        }
        
        throw new NotFoundHttpException($this->getError($model));
    }


    /**
     * 项目合同编辑默认配置
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);

        $itemModel = new Contract();
        $model = $id ? $this->findModel($id) : $itemModel->loadDefaultValues();

        $memberList = [];
        foreach (Worker::getMap() as $key => $value) {
            array_push($memberList, [
                'text' => $value,
                'value' => $key
            ]);
        }
        
        return [
            'model' => $model,
            'memberMap' => Worker::getMap(),
            'memberList' => $memberList
        ];
    }
}

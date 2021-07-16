<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-31 10:23:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-31 15:26:29
 * @Description: 
 */
namespace api\modules\v1\controllers\project;

use Yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\helpers\ResultHelper;
use common\models\member\api\Ground;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class GroundController extends OnAuthController
{
    public $modelClass =  Ground::class;


    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = [];

    public function actionList($page = 1, $limit = 10){
        $request = yii::$app->request;
        $title = $request->get('title',null);
        $pid = $request->get('pid',0);
        $query = Ground::find()
			->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $title])         
            ->andWhere(['member_id' => Yii::$app->user->identity->member_id])
            ->andWhere(['pid' => $pid]);
           
		$countQuery = clone $query;
		$data = $query
			->offset($page * $limit - $limit)
            ->limit($limit)
            ->orderBy('id desc')
			->asArray()
            ->all();
        return ResultHelper::json(200, '成功', [
            'data' => $data,
            'count' => $countQuery->count(),
            'cateDropDownList' => Ground::getEditDropDownList($pid),
        ]); 
    }

    /**
     * 单个显示
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     * @throws NotFoundHttpException
     */
    public function actionView($id)
    {
        $model = Ground::find()
            ->with('map')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        return ResultHelper::json(200, '成功', [
            'data' => $model,
        ]); 
    }


    public function actionEdit(){
        $request = yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->pid = $request->get('pid',null) ?? $model->pid;

        if ($model->load(Yii::$app->request->post(),'')) {
            $model->member_id = Yii::$app->user->identity->member_id;
            if(!$model->save())
                return ResultHelper::json(422, $this->getError($model));
        }
        
        return ResultHelper::json(200, '成功', [
            'data' => $model,
        ]); 
    }

    public function findModel($id)
	{
		/* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }

        return $model;
    }
}

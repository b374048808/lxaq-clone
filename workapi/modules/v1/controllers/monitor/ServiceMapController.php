<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-08 16:48:00
 * @Description: 
 */

namespace workapi\modules\v1\controllers\monitor;

use common\enums\StatusEnum;
use common\models\monitor\project\House;
use workapi\controllers\OnAuthController;
use common\models\monitor\project\service\Map;
use common\helpers\ArrayHelper;
use common\models\monitor\project\house\ItemMap;
use common\models\monitor\project\service\ReportMap;
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
class ServiceMapController extends OnAuthController
{
    public $modelClass = Map::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    /**
     * 
     * 任务下面关联
     * 
     * @param {*} $id
     * @param {*} $start
     * @param {*} $limit
     * @return {*}
     * @throws: 
     */    
    public function actionList($id, $start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title', NULL);
        $exist = $request->get('exist', false);

        $where = [];
        if ($title) {
            $houseModel = House::find()
                ->where(['like', 'title', $title])
                ->andWhere(['status' => StatusEnum::ENABLED])
                ->asArray()
                ->all();
            $houseIds = ArrayHelper::getColumn($houseModel, 'id', $keepKeys = true);
            $where = ['in', 'map_id', $houseIds];
            # code...
        }
        $existWhere = ($exist === 'true') ? ['report_id' => null] : [];

        $model = Map::find()
            ->with('house')
            ->where(['pid' => $id])
            ->andWhere($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere($existWhere)
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();
        $res = [];
        foreach ($model as $key => &$value) {
            array_push($res, [
                'id'    => $value['id'],
                'house_title' => $value['house']['title'],
                'is_exist' => empty($value['report_id']),
            ]);
        }
        unset($value);

        return $res;
    }

    /**
     * 任务编辑
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */
    public function actionEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);
        $model = $id ? $this->findModel($id) : new Map();

        if ($model->load($request->post(), '')) {
            $model->user_id = Yii::$app->user->identity->member_id;
            if (!$model->save()) {
                throw new NotFoundHttpException($this->getError($model));
            }
        }
        return true;
    }

    public function actionView($id)
    {
        $model = Map::find()
            ->with(['house','service','report'])
            ->where(['id' => $id])
            ->asArray()
            ->one();

        $model['files'] = json_decode($model['files'],true);
        $model['images'] = json_decode($model['images'],true);
        return $model;
    }

    /**
     * 任务详情
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id', NULL);

        $itemModel = new Map();
        if ($id) {
            $model = Map::find()
                ->with('report')
                ->where(['id' => $id])
                ->asArray()
                ->one();
            $model['images'] = $model['images']?json_decode($model['images'],true):[];
            $model['files'] = $model['files']?json_decode($model['files'],true):[];
        }else{
            $model =  $itemModel->loadDefaultValues();
        }
        

        return [
            'model' => $model,

        ];
    }
}

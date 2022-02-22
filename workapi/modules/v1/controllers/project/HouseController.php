<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 10:21:47
 * @Description: 
 */

namespace workapi\modules\v1\controllers\project;

use common\enums\house\NatureEnum;
use common\enums\house\RoofEnum;
use common\enums\house\TypeEnum;
use common\enums\NewsEnum;
use common\enums\StatusEnum;
use common\models\monitor\project\house\ItemMap;
use common\enums\WarnEnum;
use common\models\monitor\project\House;
use workapi\controllers\OnAuthController;
use yii\web\NotFoundHttpException;
use common\models\monitor\project\service\Map;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class HouseController extends OnAuthController
{
    public $modelClass = House::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    public function actionList($start = 0,$limit = 10)
    {
        $request = Yii::$app->request;
        $title = $request->get('title',NULL);
        $item_id = $request->get('item_id',NULL);
        $service_id = $request->get('service_id',NULL);
        $not_in = $request->get('not_in',NULL);
        $ids =  $request->get('ids',NULL);

        // 根据项目搜索关联房屋
        $where = [];
        if ($item_id) {
            $isIn = $not_in?'not in':'in';
             $where = [$isIn,'id',ItemMap::getHouseIds($item_id)];
        }else if($service_id){
            $isIn = $not_in?'not in':'in';
             $where = [$isIn,'id',Map::getHouseIds($service_id)];
        }else if ($ids) {
            $houseId = json_decode($ids,true);
            $isIn = $not_in?'not in':'in';
             $where = [$isIn,'id',$houseId];
        }
        
        
        $model = House::find()
            ->select(['title','id','status'])
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like','title',$title])
            ->orderBy('id desc')
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();
            foreach ($model as $key => &$value) {
                $value['warn'] = Yii::$app->services->pointWarn->getHouseWarn($value['id']);
                $value['warn_text'] = WarnEnum::getValue($value['warn']);
                $value['warn_type'] = WarnEnum::$tagType[$value['warn']];
            }
            unset($value);
        
        return $model;
    }

    /**
     * 编辑
     * 
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public function actionEdit(){
        $request = Yii::$app->request;
        $id = $request->get('id',NULL);
        $model = $id?$this->findModel($id):new House();

        if($model->load($request->post('item'),'')) {    
            if($model->isNewRecord)        
                $model->entry_id = Yii::$app->user->identity->member_id;
            if(!$model->save()){
                throw new NotFoundHttpException($this->getError($model));

            }
        }
        return true;
    }

    /**
     * 详情
     * 
     * @param {*} $id
     * @return {*}
     * @throws: 
     */    
    public function actionView($id){
        $model = $this->findModel($id);


        return $model;
    }

    public function actionDefault()
    {
        $request = Yii::$app->request;
        $id = $request->get('id',NULL);

        $HouseModel = new House();

        $model = $id?$this->findModel($id):$HouseModel->loadDefaultValues();

        return [
            'model' => $model,
            'type_enum' => TypeEnum::getMap(),
            'nature_enum' => NatureEnum::getMap(),
            'roof_enum' => RoofEnum::getMap(),
            'news_enum'  => NewsEnum::getMap(),
        ];
    }

}

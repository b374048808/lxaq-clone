<?php
namespace api\modules\v1\controllers\project;

use Yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\member\HouseMap;
use common\models\member\api\GroundMap;
use common\models\monitor\project\House;
use common\helpers\ResultHelper;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class GroundMapController extends OnAuthController
{
    public $modelClass =  GroundMap::class;


    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $optional = [];

    /**
     * 根据分组ID返回关联列表
     * 
     * @param number|number|int
     * @param 分页|数量|类型
     * @return Array|Number
     * @throws: 
     */    
    public function actionList($page = 1, $limit = 10, $type = false)
    {
        $request = yii::$app->request;
        // 分组id
        $groundId = $request->get('groundId', null);
        $title = $request->get('title', null);  
        $houseIds = HouseMap::getHouseMap(Yii::$app->user->identity->member_id);
        $mapIds = GroundMap::find()
            ->where(['ground_id' => $groundId])
            ->andWhere(['in', 'house_id', $houseIds])
            ->asArray()
            ->all();
        $mapIds  = ArrayHelper::getColumn($mapIds, 'house_id');
        $where = $type ? ['not in', 'id', $mapIds] : ['in', 'id', $mapIds];

        $query = House::find()
            ->where($where)
            ->andWhere(['in', 'id', $houseIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'title', $title]);    //带户主查询
        $countQuery = clone $query;
        $data = $query
            ->offset($page * $limit - $limit)   
            ->limit($limit)
            ->asArray()
            ->all();

        return ResultHelper::json(200, '成功', [
            'data' => $data,
            'count' => $countQuery->count()
        ]);
    }


    /**
     * 删除关联
     * 
     * @param  number
     * @return  bool
     * @throws: 
     */    
    public function actionDele($id){
        $request = yii::$app->request;

        $groundId = $request->get('groundId', null);

        return GroundMap::deleteAll(['ground_id' => $groundId,'house_id' => $id])
            ? ResultHelper::json(200, '成功')
            : ResultHelper::json(422, '失败');
    }

    /**
     * 批量删除
     * 
     * @param number|array
     * @return bool
     * @throws: 
     */    
    public function actionDeleteAll($groundId){
        $request = yii::$app->request;

        $ids = $request->get('ids', null);

        return GroundMap::deleteAll([
            'and',
            ['=','ground_id', $groundId],
            ['in','house_id',$ids]
        ])
            ? ResultHelper::json(200, '成功')
            : ResultHelper::json(422, '失败');
    }


    /**
     * 分组内添加建筑物
     * 
     * @param number|array
     * @return bool
     * @throws: 
     */    
    public function actionCreate()
    {
        $request = yii::$app->request;
        $groundId = $request->post('id');
        $houseIds =  $request->post('houseIds');
        return GroundMap::addManager($groundId, $houseIds);
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

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-03 16:15:47
 * @Description: 
 */

namespace workapi\modules\v1\controllers\project;

use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\House;
use workapi\controllers\OnAuthController;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class DeviceController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex($start = 0,$limit = 10)
    { 

        $request = Yii::$app->request;
        $title = $request->get('title',NULL);
        
        $model = House::find()
            ->select(['title','id','status'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like','title',$title])
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
     * 测试查询方法
     *
     * 注意：该方法在 main.php 文件里面的 extraPatterns 单独配置过才正常访问
     *
     * @return string
     */
    public function actionSearch()
    {
        return '测试查询';
    }
}

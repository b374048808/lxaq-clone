<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-03 15:39:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-03 17:02:04
 * @Description: 
 */

namespace workapi\modules\v1\controllers\project;

use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\House;
use common\models\monitor\project\point\Value;
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
class ValueController extends OnAuthController
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
        $houseId = $request->get('houseId',NULL);
        $pid = $request->get('pid',NULL);
        $where = [];
        if ($houseId) {
            $pointIds = House::getPointColumn($houseId);
            $where = ['in','pid',$pointIds];
            # code...
        }
        if ($pid) {
            $where = ['pid' => $pid];
            # code...
        }
        
        $model = Value::find()
            ->where($where)
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['state' => ValueStateEnum::ENABLED])
            ->offset($start)
            ->limit($limit)
            ->orderBy('event_time desc')
            ->asArray()
            ->all();
        
        foreach ($model as $key => &$value) {
            $value['warn_text'] = WarnEnum::getValue($value['warn']);
            $value['warn_type'] = WarnEnum::$tagType[$value['warn']];
            $value['event_time'] = date('m-d H:i');
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
    public function action()
    {
        return '测试查询';
    }
}

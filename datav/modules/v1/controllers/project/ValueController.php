<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:42:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-23 18:33:27
 * @Description: 
 */

namespace datav\modules\v1\controllers\project;

use yii;
use api\controllers\OnAuthController;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\helpers\UploadHelper;
use common\models\monitor\project\House;
use common\models\monitor\project\point\Value;

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
    public $modelClass = Value::class;

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
    public function actionIndex($start = null)
    { 
        $limit = $start?'':10;
        $model = Value::find()
            ->with(['house','parent'])
            ->where(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['>','event_time',$start])
            ->limit($limit)
            ->orderBy('event_time desc')
            ->asArray()
            ->all();
        
        foreach ($model as $key => &$value) {
            $value['house'] = $value['house']['title'];
            $value['point'] = $value['parent']['title'];
            $value['point_type'] = PointEnum::getValue($value['parent']['type']);
            unset($value['parent']);
            $value['warn_text'] = WarnEnum::getValue($value['warn']);
            $value['state_text'] = ValueStateEnum::getValue($value['state']);
            $value['type_text'] = ValueTypeEnum::getValue($value['type']);
            $value['prev'] = floatval(Value::getPrevValue($value['id']));
            $value['value'] = floatval($value['value']);
            $value['new'] = true;
        }
        unset($value);

        return $model;

    }

}

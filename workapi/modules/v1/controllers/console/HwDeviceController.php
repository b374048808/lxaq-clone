<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-26 14:35:35
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-09 10:59:05
 * @Description: 
 */

namespace workapi\modules\v1\controllers\console;

use workapi\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\models\console\iot\huawei\Device;
use common\models\console\iot\huawei\Directive;
use common\models\console\iot\huawei\Service;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package workapi\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class HwDeviceController extends OnAuthController
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
    public function actionIndex()
    {
        return 'index';
    }

    public function actionList($start = 0, $limit = 10)
    {
        $request = Yii::$app->request;
        $number = $request->get('number', NULL);

        $model = Device::find()
            ->select(['number', 'id', 'status'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['like', 'number', $number])
            ->offset($start)
            ->limit($limit)
            ->asArray()
            ->all();
        foreach ($model as $key => &$value) {
            # code...
            $value['online'] = Device::getOnline($value['id']);
        }
        unset($value);

        return $model;
    }

    public function actionView($id)
    {
        $request  = Yii::$app->request;
        $model = Device::find()
            ->with(['newValue','directive'])
            ->where(['id' => $id])
            ->asArray()
            ->one();
        
        $model['newValue']['value'] = json_decode($model['newValue']['value']);
        $model['newValue']['service'] = Service::find()
            ->with('attr')
            ->where(['string' => $model['newValue']['serviceType']])
            ->andWhere(['pid' => $model['pid']])
            ->asArray()
            ->one();

        return $model;
    }

    public function actionGetDirective($id){
        $request  = Yii::$app->request;
        $directive_id = $request->post('directive_id',NULL);
        $str = $request->post('content',NULL);
        return Yii::$app->services->huaweiDirective->send($id, $directive_id,$str);

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
    }
}

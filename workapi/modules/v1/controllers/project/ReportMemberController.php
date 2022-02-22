<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-23 12:01:02
 * @Description: 
 */

namespace workapi\modules\v1\controllers\project;

use common\models\monitor\project\house\Report;
use workapi\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\models\monitor\project\house\ReportMember;
use Yii;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package workapi\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class ReportMemberController extends OnAuthController
{
    public $modelClass = Report::class;

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = [];

    /**
     * 列表
     * 
     * @param {*} $start
     * @param {*} $limit
     * @return {*}
     * @throws: 
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $pid = $request->get('pid', NULL);

        $model = ReportMember::find()
            ->with('member')
            ->where(['pid' => $pid])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        foreach ($model as $key => &$value) {
            
        }
        unset($value);

        return $model;
    }
}

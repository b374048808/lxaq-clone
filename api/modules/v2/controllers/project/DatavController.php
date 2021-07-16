<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-14 14:57:29
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 15:40:13
 * @Description: 
 */

namespace api\modules\v2\controllers\project;

use Yii;
use api\controllers\OnAuthController;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\models\member\HouseMap;
use common\models\monitor\project\House;
use common\models\monitor\project\log\WarnLog;
use common\enums\PointEnum;
use common\models\monitor\project\Point;
use yii\data\Pagination;
use common\helpers\ArrayHelper;
/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v1\controllers
 * @property \yii\db\ActiveRecord $modelClass
 * @author jianyan74 <751393839@qq.com>
 */
class DatavController extends OnAuthController
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
        $houseIds = Yii::$app->services->memberHouse->getHouseId(Yii::$app->user->identity->member_id);

        // 
        $model =  House::find()
            ->select(['id', 'title', 'lng', 'lat', 'status'])
            ->with(['warn'])
            ->where(['in', 'id', $houseIds])
            ->andWhere(['and',['>','lat',0],['>','lng',0]])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        $info['warns'] = [];
        //预警判断，只显示三级预警
        foreach (WarnEnum::getMap() as $key => $value) {
            if ($key < 4) {
                $info['warns'][$key] = [
                    'name'  => $key,
                    'title'  => $value,
                    'value' => 0
                ];
            }
        }

        $info['map'] = [];
        // 遍历点位输出
        foreach ($model as $value) {
            if ($value['lat'] > 0 && $value['lng'] > 0) {
                $warn = Yii::$app->services->pointWarn->getHouseWarn($value['id']);
                array_push($info['map'], [
                    'id'    => $value['id'],
                    'name' => $value['title'],
                    'labelOffset' => [0, 0],
                    'lat' => $value['lat'],
                    'lng' => $value['lng'],
                    'state' => $warn,
                ]);
                $info['warns'][$warn]['value']++;
            }
        }

        return $info;
    }

}

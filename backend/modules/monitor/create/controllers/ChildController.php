<?php

namespace backend\modules\monitor\create\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\monitor\create\Child;
use common\models\monitor\project\Point;
use common\models\monitor\project\House;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\behaviors\ActionLogBehavior;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ChildController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Child::class;

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'], // 登录
                    ],
                ],
            ],
            'actionLog' => [
                'class' => ActionLogBehavior::class
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'add-points' => ['post']
                ]
            ]
        ];
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $simple_id = $request->get('simple_id', NULL);
        $title = $request->get('title', NULL);
        $type = $request->get('type', 0);
        $where = [];
        if ($type > 0) {
            $where = ['type' => $type];
        }
        $andWhere = [];
        if ($title) {
            $houseModel = House::find()
                ->where(['status' => StatusEnum::ENABLED])
                ->andWhere(['like','title',$title])
                ->asArray()
                ->all();
            $houseIds = ArrayHelper::getColumn($houseModel,'id', $keepKeys = true);
            $andWhere = ['in','id',$houseIds];
        }
        $query = Point::find()
            ->where(['not in', 'id', Child::getHouseMap($simple_id)])
            ->andWhere($andWhere)
            ->andFilterWhere($where)
            ->orderBy('id desc');


        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);


        return $this->renderAjax('ajax-edit', [
            'dataProvider' => $dataProvider,
            'simple_id' => $simple_id
        ]);
    }

    /**
     * 批量添加监测点
     * @param number simple_id
     * @param array data
     * @return boole
     * @throws: 
     */
    public function actionAddPoints()
    {

        $request = Yii::$app->request;
        $simple_id = $request->get('simple_id', NULL);
        $data = $request->post('data', []);

        return Child::addPoints($simple_id, $data);
    }

    /**
     * 批量删除监测点关联
     * @param array data
     * @return boole
     * @throws: 
     */
    public function actionDeleteAll()
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);
        return Child::deleteAll(['in', 'id', $data]);
    }


    /**
     * 批量删除监测点关联
     * @param array data
     * @return boole
     * @throws: 
     */
    public function actionRandAll()
    {
        $request = Yii::$app->request;
        $data = $request->post('data', []);

        $res = true;
        foreach ($data as $key => $value) {            
            $result = Yii::$app->services->createSimple->setOneValue($value);
            if (!$result) {
                $res = false;
            }
            # code...
        }
        return $res;
    }
}

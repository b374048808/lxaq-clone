<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-06 10:27:19
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-08 10:41:17
 * @Description: 
 */

namespace backend\modules\monitor\service\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use backend\modules\monitor\service\forms\HouseGroundForm;
use common\enums\mini\MessageActionEnum;
use common\enums\mini\MessageReasonEnum;
use common\enums\monitor\ItemTypeEnum;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\models\worker\Worker;
use common\models\monitor\project\service\Service;
use common\enums\VerifyEnum;
use common\helpers\ArrayHelper;
use common\models\monitor\project\Item;
use common\models\monitor\project\service\Audit;
use common\models\monitor\project\service\Map;
use common\helpers\ExcelHelper;
use common\models\monitor\project\Ground;
use common\models\monitor\project\GroundMap;
use common\models\worker\MiniMessage;
use yii\data\Pagination;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Service::class;

    /**
     * 首页
     * 
     * @return mixed
     */

    public function actionIndex()
    {
        $request = Yii::$app->request;
        $from_date = $request->get('from_date', NULL) ? strtotime($request->get('from_date', NULL)) : NULL;
        $to_date = $request->get('to_date', NULL) ? strtotime($request->get('to_date', NULL)) : NULL;

        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['between', 'created_at', $from_date, $to_date]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }


    /**
     * @return mixed|string|\yii\console\Response|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = $this->findModel($id);
        $model->pid = $request->get('pid', null) ?? $model->pid; // 父id

        // ajax 验证
        $this->activeFormValidate($model);

        if ($model->load(Yii::$app->request->post())) {
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->renderAjax($this->action->id, [
            'model' => $model,
            'roles' => Worker::getMap(),
            'items' => Item::getDropDown()
        ]);
    }


    /**
     * ajax编辑/创建审核
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxAudit($id)
    {
        $model = $this->findModel($id);
        $formModel = new Audit();
        $formModel->verify = $model->audit;
        $formModel->pid = $id;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($formModel->load(Yii::$app->request->post())) {
            $db = Yii::$app->db;
            // 在主库上启动事务
            $transaction = $db->beginTransaction();
            try {
                $model->audit = $formModel->verify;
                $formModel->remark = '管理员' . Yii::$app->user->identity->username . '更新状态为' . VerifyEnum::getValue($model->audit);
                $formModel->ip = Yii::$app->request->userIP;
                if (!($formModel->save() && $model->save()))
                    return $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
            return $this->redirect(Yii::$app->request->referrer);
        }

        return $this->renderAjax($this->action->id, [
            'model' => $formModel,
        ]);
    }

    /**
     * 上传报告
     *
     * @return mixed
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        $query = Map::find()
            ->where(['pid' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->render('view', [
            'model' => $model,
            'dataProvider'  => $dataProvider
        ]);
    }

    /**
     * 还原
     *
     * @param $id
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = $this->findModel($id);
        $model->status = StatusEnum::ENABLED;
        if ($model->save()) {
            return $this->message("还原成功", $this->redirect(['recycle']));
        }

        return $this->message("还原失败", $this->redirect(['recycle']), 'error');
    }



    /**
     * 回收站
     *
     * @return mixed
     */
    public function actionRecycle()
    {
        $data = $this->modelClass::find()
            // ->with(['user','item','member'])
            ->where(['<', 'status', StatusEnum::DISABLED]);
        $pages = new Pagination(['totalCount' => $data->count(), 'pageSize' => $this->pageSize]);
        $models = $data->offset($pages->offset)
            ->orderBy('id desc')
            ->limit($pages->limit)
            ->all();

        return $this->render($this->action->id, [
            'models' => $models,
            'pages' => $pages
        ]);
    }

    /**
     * 导出Excel
     *
     * @return bool
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function actionExport()
    {
        // [名称, 字段名, 类型, 类型规则]
        $request = Yii::$app->request;
        $from_date = $request->get('from_date', NULL) ? strtotime($request->get('from_date', NULL)) : NULL;
        $to_date = $request->get('to_date', NULL) ? strtotime($request->get('to_date', NULL)) : NULL;
        //默认输出一周数据


        $data = Audit::find()
            ->with(['item', 'user'])
            ->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['between', 'created_at', $from_date, $to_date])
            ->asArray()
            ->all();
        foreach ($data as $key => &$value) {
            $value['username'] = $value['item']['realname'];
            $value['item_title'] = $value['item']['title'];
        }
        unset($value);
        $header = [
            ['项目', 'item_title', 'text'],
            ['说明', 'remark', 'text'],
            ['人员', 'username', 'text'],
            ['提交状态', 'verify', 'selectd', VerifyEnum::getMap()],
            ['日期', 'created_at', 'date', 'Y-m-d H:i:s'],
        ];
        return ExcelHelper::exportData($data, $header,  '数据导出_' . time());
    }


    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionHouseGround($id)
    {
        $id = Yii::$app->request->get('id', '');
        $model = new HouseGroundForm();

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            $groundModel = GroundMap::find()
                ->where(['ground_id' => $model->ground_id])
                ->andWhere(['not in', 'house_id', Map::getHouseIds($id)])
                ->asArray()
                ->all();
            $houseIds = ArrayHelper::getColumn($groundModel, 'house_id', $keepKeys = true);
            if ($houseIds) {
                Map::addHouses($id, $houseIds);
                return $this->redirect(['view', 'id' => $id]);
            } else {
                return  $this->message('房屋已存在，不能重复添加', $this->redirect(['view', 'id' => $id]), 'error');
            }
        }

        return $this->renderAjax('house-ground', [
            'model' => $model,
            'id' => $id,
            'menuDropDownList' => Yii::$app->services->houseGround->getTitleDown(),
        ]);
    }

    public function actionRemind($id)
    {
        $model = $this->findModel($id);
        $messageModel = MiniMessage::find()
            ->where(['member_id' => $model->manager, 'is_read' => 0])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['target_type' => MessageReasonEnum::SERVICE_VERIFY, 'target_id' => MessageActionEnum::VERIFY_CREATE])
            ->orderBy('id desc')
            ->one();
        $data = [
            'thing1' => [
                'value' => mb_substr(($model->item->title ?: '未关联项目'), 0, 15),
            ],
            'thing2' => [
                'value' => mb_substr($model->description, 0, 20)
            ],
            'time3' => [
                'value' => ($model->start_time ? date('Y-m-d', $model->start_time) : date('Y-m-d')),
            ],
            'name4' => [
                'value' => $model->publisher ? Worker::getRealname($model->publisher) : '管理员'
            ],
            'thing14' => [
                'value' => ItemTypeEnum::getValue($model->item->type),
            ],
        ];
        if ($messageModel){
            Yii::$app->services->workerMiniMessage->send($messageModel->id, $data, 'pages/service-view/service-view?itemId=' . $id);
            return  $this->message('已发送', $this->redirect(['view', 'id' => $id]), 'success');
        }
        return  $this->message('用户未订阅', $this->redirect(['view', 'id' => $id]), 'error');
    }
}

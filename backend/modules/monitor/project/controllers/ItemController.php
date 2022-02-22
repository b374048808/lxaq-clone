<?php

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use backend\modules\monitor\project\forms\ItemExportForm;
use common\enums\monitor\ItemStepsEnum;
use common\enums\monitor\ItemTypeEnum;
use common\helpers\ExcelHelper;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\models\monitor\project\Item;
use common\models\base\SearchModel;
use common\models\monitor\project\House;
use common\models\monitor\project\house\ItemMap;
use common\models\monitor\project\item\Contract;
use common\models\monitor\project\item\VerifyLog;
use yii\data\ActiveDataProvider;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ItemController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Item::class;
    /**
     * 首页
     * 
     * @return mixed
     */
    public function actionIndex()
    {
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
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed
     */
    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        if ($model->load(Yii::$app->request->post())) {
            $model->start_time = strtotime($model->start_time);
            $model->end_time = strtotime($model->end_time);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }



    /**
     * 回收站
     * 
     * @return mixed
     */
    public function actionRecycle()
    {
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
            ->andWhere(['=', 'status', StatusEnum::DELETE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }

    /**
     * 还原
     * 
     * @param int
     * @return mixed
     */
    public function actionShow($id)
    {
        $model = Item::findOne($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
            ? $this->redirect(['recycle'])
            : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
    }

    /**
     * 详情|项目下所有房屋列表
     *
     * @return mixed|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $request = Yii::$app->request;

        $verifyModel = new VerifyLog();
        $verifyModel->setScenario('backendSteps');

        $model = $this->findModel($id);

        $query = ItemMap::find()
            ->where(['item_id' => $id]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);



        return $this->render($this->action->id, [
            'model' => $model,
            'dataProvider' => $dataProvider,
            'verifyModel'   => $verifyModel,
        ]);
    }

    public function actionSteps($id, $step = true)
    {

        return  Item::updateAllCounters(['steps' => $step ? 1 : -1], ['id' => $id])
            ? $this->redirect(['view', 'id' => $id])
            : $this->message('系统繁忙！', $this->redirect(['view', 'id' => $id]), 'error');
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
        $formModel = new VerifyLog();
        $formModel->verify = $model->audit;
        $formModel->map_id = $id;
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



    public function actionContract($pid)
    {
        $request = Yii::$app->request;

        $model = new Contract();
        if ($model->load($request->post())) {
            $model->pid = $pid;
            $model->event_time = strtotime($model->event_time);
            if ($model->save()) {
                Item::updateAllCounters(['steps' => 1], ['id' => $pid]);
                return $this->redirect(['view', 'id' => $pid]);
            } else {
                return $this->message($this->getError($model), $this->redirect(['view', 'id' => $pid]), 'error');
            }
        }
        return  $this->message('系统繁忙！', $this->redirect(['view', 'id' => $pid]), 'error');
    }


    public function actionAjaxHouse($id)
    {
        // 已选择房屋列表
        $houseIds = ItemMap::getHouseIds($id);


        $query = House::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->andWhere(['not in', 'id', $houseIds]);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => [
                'pageSize' => 20,
            ]
        ]);

        return $this->renderAjax($this->action->id, [
            'dataProvider' => $dataProvider,
            'id' => $id,
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

        $formModel = new ItemExportForm();

        if ($formModel->load($request->post(), '')) {
            $data = Item::find()
                ->with('user')
                ->andWhere(['=', 'status', StatusEnum::ENABLED])
                ->andWhere(['between', 'created_at', strtotime($formModel['from_date']), strtotime("+1 day", strtotime($formModel['to_date']))])
                ->andWhere(['audit' => VerifyEnum::PASS])
                ->asArray()
                ->all();
            foreach ($data as $key => &$value) {
                $value['work_name'] = $value['user']['realname'];
                $value['province'] = Yii::$app->services->provinces->getName($value['province_id']);
                $value['city'] = Yii::$app->services->provinces->getName($value['city_id']);
                $value['area'] = Yii::$app->services->provinces->getName($value['area_id']);
            }
            unset($value);
            $header = [
                ['项目名称', 'title', 'text'],
                ['编号', 'number', 'text'],
                ['步骤', 'steps', 'selectd', ItemStepsEnum::getMap()],
                ['预计金额', 'money', 'text'],
                ['委托方', 'entrust', 'text'],
                ['归属人', 'belonger', 'text'],
                ['联系人', 'contact', 'text'],
                ['联系方式', 'mobile', 'text'],
                ['类型', 'type', 'selectd', ItemTypeEnum::getMap()],
                ['省份', 'province', 'text'],
                ['城市', 'city', 'text'],
                ['区', 'area', 'text'],
                ['默认地址', 'address', 'text'],
                ['立项时间', 'event_time', 'date', 'Y-m-d H:i:s'],
                ['说明', 'description', 'text'],
                ['项目需求', 'demand', 'text'],
                ['项目概述', 'survey', 'text'],
                ['创建人员', 'work_name', 'text'],
                ['创建时间', 'created_at', 'date', 'Y-m-d H:i:s'],
            ];
            return ExcelHelper::exportData($data, $header, '项目数据_' . date('Ymd'));
        }

        return $this->renderAjax($this->action->id, [
            'formModel' => $formModel
        ]);
    }
}

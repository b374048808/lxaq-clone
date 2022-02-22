<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-29 07:30:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-14 15:04:44
 * @Description: 
 */

namespace backend\modules\monitor\project\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\models\monitor\project\house\Report;
use yii\data\ActiveDataProvider;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\helpers\ExcelHelper;
use common\models\common\ReportLog;
use common\models\monitor\project\house\ReportVerify;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ReportController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Report::class;
    /**
     * 首页
     * 
     * @return mixed
     */
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
            'partialMatchAttributes' => ['title'],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>', 'status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel
        ]);
    }


    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionAjaxEdit()
    {
        $id = Yii::$app->request->get('id', '');
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid', null) ?? $model->pid; // 父id

        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load(Yii::$app->request->post())) {
            return $model->save()
                ? $this->redirect(Yii::$app->request->referrer)
                : $this->message($this->getError($model), $this->redirect(Yii::$app->request->referrer), 'error');
        }

        return $this->renderAjax('ajax-edit', [
            'model' => $model,
        ]);
    }

    /**
     * 详情|项目下所有房屋列表
     *
     * @return mixed|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        return $this->renderAjax($this->action->id, [
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
        $model = Report::findOne($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
            ? $this->redirect(['recycle'])
            : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
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
        $formModel = new ReportVerify();
        $formModel->verify = $model->verify;
        $formModel->pid = $id;
        // ajax 校验
        $this->activeFormValidate($model);
        if ($formModel->load(Yii::$app->request->post())) {
            $db = Yii::$app->db;
            // 在主库上启动事务
            $transaction = $db->beginTransaction();
            try {
                $model->verify = $formModel->verify;
                $formModel->remark = '管理员' . Yii::$app->user->identity->username . '更新状态为' . VerifyEnum::getValue($model->verify);
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
     * 删除
     *
     * @param $id
     * @return mixed
     * @throws \Throwable
     * @throws \yii\db\StaleObjectException
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $str = $_SERVER['DOCUMENT_ROOT'].'/'.substr($model->file,strpos($model->file,'/attachment')+1);
        if ($model->delete()) {
            // 删除文件
            if(file_exists($str)){
                unlink($str);
            }
            return $this->message("删除成功", $this->redirect(Yii::$app->request->referrer));
        }

        return $this->message("删除失败", $this->redirect(Yii::$app->request->referrer), 'error');
    }

    // 删除目录，未实现
    function deldir($dir)
    {
        //先删除目录下的文件：      
        $dh = opendir($dir);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $dir . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deldir($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($dir)) {
            return true;
        } else {
            return false;
        }
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
        $where = $request->get('SearchModel', '');
        $data = Report::find()
            ->with('user')
            ->andWhere(['>', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['like', 'description', $where->description])
            ->andFilterWhere(['verify' => $where->verify])
            ->andFilterWhere(['like', 'file_name', $where->file_name])
            ->asArray()
            ->all();
        foreach ($data as $key => &$value) {
            $value['user_name'] = $value['user']['realname'];
            # code...
        }
        unset($value);
        $header = [
            ['人员', 'user_name', 'text'],
            ['结论', 'description', 'text'],
            ['审核', 'verify', 'selectd', VerifyEnum::getMap()],
            ['创建时间', 'created_at', 'date', 'Y-m-d H:i:s'],
        ];
        return ExcelHelper::exportData($data, $header, time() . '数据导出_' . time());
    }
}

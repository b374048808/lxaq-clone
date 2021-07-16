<?php

namespace backend\modules\console\ali\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\models\console\iot\ali\Device;
use common\models\base\SearchModel;
use common\models\console\iot\ali\Directive;
use backend\modules\console\ali\forms\DirectiveForm;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\AliMap;
use common\models\sim\vlist\Card;

/**
 * 产品
 *
 * Class ProductController
 * @package addons\RfArticle\backend\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DeviceController extends BaseController
{
    use Curd;
    /**
     * @var Adv
     */
    public $modelClass = Device::class;

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
            'relations' => ['product'  => ['name']],    //产品名称
            'partialMatchAttributes' => ['product_name', 'title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', Device::tableName() . '.status', StatusEnum::DISABLED]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'onLine' => Yii::$app->services->aliDevice->getOnLineCount()
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
        $request = Yii::$app->request;
        $id = $request->get('id', '');
        $model = $this->findModel($id);
        // ajax 校验
        $this->activeFormValidate($model);
        if ($model->load($request->post())) {
            $model->expiration_time = strtotime($model->expiration_time);
            return $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }
        $model->expiration_time =  $model->isNewRecord ? date('Y-m-d', strtotime('+1 year')) : date('Y-m-d', $model->expiration_time);
        return $this->renderAjax('ajax-edit', [
            'model' => $model,
            'cards' => Card::getMap()
        ]);
    }

    /**
     * 编辑/创建
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionView($id)
    {
        $model = $this->findModel($id);

        // 设备使用情况
        $pointIds = AliMap::getPointColumn($id);
        $pointModel = Point::find()
            ->with(['house','newValue'])
            ->where(['in','id',$pointIds])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        return $this->render($this->action->id, [
            'model' => $model,
            'pointModel'=> $pointModel
        ]);
    }

    /**
     * 查看命令行
     *
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionDirective($id)
    {
        // p(hex2bin('0D0A2B49434349443A2038393836303437333130323037303234383030350D0A0D0A4F4B0D0A'));exit;
        $request  = Yii::$app->request;
        $model = Device::find()
            ->with('directive')
            ->where(['id' => $id])
            ->asArray()
            ->one();

        return $this->render($this->action->id, [
            'model' => $model,
        ]);
    }

    /**
     * 发布命令
     *
     * @param number id
     * @param number directive_id
     * @return mixed|string|\yii\web\Response
     * @throws \yii\base\ExitException
     */
    public function actionGetDirective($id, $directive_id)
	{
       
		$model = $this->findModel($id);
		$request = Yii::$app->request;
		$directiveModel = Directive::findOne($directive_id);
		$formModel = new DirectiveForm();

        
         // ajax 校验
        $this->activeFormValidate($formModel);
		if ($formModel->load($request->post())) {  
            $directiveModel->content = str_replace('@RES', $formModel->value, $directiveModel->content);                     
			
		}	
        // 判断是否选择带参命令
        if (strpos($directiveModel->content, '@RES') && !$request->isPost) {
			return $this->renderAjax($this->action->id, [
				'model' => $formModel,
				'id' => $model->id,
				'directive_id' => $directiveModel->id,
			]);
		}

        return Yii::$app->services->aliDirective->send($id, $directive_id, $formModel->value)
                ? $this->message('下发成功!', $this->redirect(['directive', 'id' => $model->id]))
                : $this->message('下发失败!', $this->redirect(['directive', 'id' => $model->id]),'error'); 
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
            'relations' => ['product'  => ['name']],    //产品名称
            'partialMatchAttributes' => ['product_name', 'title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC
            ],
            'pageSize' => $this->pageSize
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['=', Device::tableName() . '.status', StatusEnum::DELETE]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'onLine' => Yii::$app->services->aliDevice->getOnLineCount()
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
        $model = $this->findModel($id);

        $model->status = StatusEnum::ENABLED;

        return $model->save()
            ? $this->redirect(['recycle'])
            : $this->message($this->getError($model), $this->redirect(['recycle']), 'error');
    }
}

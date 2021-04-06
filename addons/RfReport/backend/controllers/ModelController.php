<?php

namespace addons\RfReport\backend\controllers;

use Yii;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use common\traits\MerchantCurd;
use addons\RfReport\common\models\Model;
use addons\RfReport\common\models\CharMap;
use addons\RfReport\common\models\Char;

/**
 * Class CateController
 * @package addons\RfReport\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ModelController extends BaseController
{
    use MerchantCurd;
    /**
     * @var Model
     */
    public $modelClass = Model::class;
    /**
     * 首页
     *
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => $this->modelClass,
            'scenario' => 'default',
            'partialMatchAttributes' => ['title'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);
        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['>=', 'status', StatusEnum::DISABLED]);
        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'cates' => Yii::$app->rfReportService->cate->getMapList(),
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

        // 设置选中字符
        $charMap = CharMap::getCharsByModelId($id);
        $model->chars = array_column($charMap,'char_id');

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //更新模版字符
            CharMap::addChars($model->id,$model->chars);
            return $this->redirect(['index']);
        }
        $items = Yii::$app->rfReportService->doc->getMapList();
        unset($items[$model['id']]);
        return $this->render($this->action->id, [
            'model' => $model,
            'items' => $items,
            'cates' => Yii::$app->rfReportService->cate->getMapList(),
            'chars' => Char::getCheckTags()
        ]);
    }

    

    /**
     * 返回模型
     *
     * @param $id
     * @return \yii\db\ActiveRecord
     */
    protected function findModel($id)
    {
        /* @var $model \yii\db\ActiveRecord */
        if (empty($id) || empty(($model = $this->modelClass::findOne($id)))) {
            $model = new $this->modelClass;
            return $model->loadDefaultValues();
        }
        return $model;
    }
}

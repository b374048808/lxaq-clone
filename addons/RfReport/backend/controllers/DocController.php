<?php

namespace addons\RfReport\backend\controllers;

use addons\RfReport\common\models\Char;
use Yii;
use common\models\base\SearchModel;
use common\enums\StatusEnum;
use common\traits\MerchantCurd;
use addons\RfReport\common\models\Doc;
use PhpOffice\PhpWord\TemplateProcessor;
use addons\RfReport\common\models\CharMap;
use addons\RfReport\common\models\Model;

/**
 * Class DocController
 * @package addons\RfOnlineDoc\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DocController extends BaseController
{
    use MerchantCurd;

    /**
     * @var Doc
     */
    public $modelClass = Doc::class;

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
            'items' => Yii::$app->rfOnlineDocService->doc->getMapList(),
            'cates' => Yii::$app->rfOnlineDocService->cate->getMapList(),
        ]);
    }


    public function actionEdit()
    {
        $id = Yii::$app->request->get('id', null);
        $model = $this->findModel($id);
        $model->pid = Yii::$app->request->get('pid') ?: '';
        $chars = CharMap::getCharsByModelId($model->pid);
        if ($model->load(Yii::$app->request->post())) {
            $m_Model = Model::findOne($model->pid);
            $data = Yii::$app->request->post('Char');
            $widths = Yii::$app->request->post('width');
            $heights = Yii::$app->request->post('height');
            $templateProcessor = new TemplateProcessor($m_Model->file);
            foreach ($data as $key => $value) {
                $CharModel = Char::findOne($key);
                
                if ($CharModel->type == Char::CHAR) {
                    $templateProcessor->setValue($CharModel->char, $value);
                } else {
                    $templateProcessor->setImageValue($CharModel->char, ['path' => $value,'width' => $widths[$key],'height' => $heights[$key]]);
                }
            }
            $url = 'word/' . time() . '.docx';
            $templateProcessor->saveAs($url);
            $model->file = Yii::$app->request->getHostInfo() . '/backend/' . $url;
            return  $model->save()
                ? $this->redirect(['index'])
                : $this->message($this->getError($model), $this->redirect(['index']), 'error');
        }

        return $this->render($this->action->id, [
            'model' => $model,
            'chars' => $chars
        ]);
    }
}

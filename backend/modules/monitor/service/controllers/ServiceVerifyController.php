<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-05 15:54:33
 * @Description: 
 */

namespace backend\modules\monitor\service\controllers;

use Yii;
use common\traits\Curd;
use backend\controllers\BaseController;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use common\models\base\SearchModel;
use common\models\monitor\project\service\Audit;
use common\models\monitor\project\service\ServiceCate;
use common\helpers\ExcelHelper;

/**
 * 文章分类
 *
 * Class ArticleCateController
 * @package addons\RfArticle\merchant\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class ServiceVerifyController extends BaseController
{
    use Curd;

    /**
     * @var ServiceCate
     */
    public $modelClass = Audit::class;

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $from_date = $request->get('from_date',NULL)?strtotime($request->get('from_date')):strtotime(date('Y-m-d',strtotime('-1 month')));
        $to_date = $request->get('to_date',NULL)?strtotime($request->get('to_date')):strtotime(date('Y-m-d'));

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
            ->andFilterWhere(['between','created_at',$from_date,$to_date]);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'from_date' => $from_date,
            'to_date' => $to_date
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
        $from_date = $request->get('from_date',NULL)?strtotime($request->get('from_date',NULL)):NULL;
        $to_date = $request->get('to_date',NULL)?strtotime($request->get('to_date',NULL)):NULL;
		//默认输出一周数据


		$data = Audit::find()
            ->with(['item','user'])
			->andWhere(['>=', 'status', StatusEnum::DISABLED])
            ->andFilterWhere(['between','created_at',$from_date,$to_date])
			->asArray()
			->all();
        foreach ($data as $key => &$value) {
            $value['username'] = $value['item']['realname'];
            $value['item_title'] = $value['item']['title'];
            $value['member_name'] = $value['member']['realname'];
        }
        unset($value);
		$header = [
            ['任务负责人', 'member_name', 'text'],
            ['项目', 'item_title', 'text'],
            ['说明', 'remark', 'text'],
			['人员', 'username', 'text'],
			['提交状态', 'verify', 'selectd',VerifyEnum::getMap()],
			['日期', 'created_at', 'date', 'Y-m-d H:i:s'],
		];
		return ExcelHelper::exportData($data, $header,  '数据导出_' . time());
	}

}
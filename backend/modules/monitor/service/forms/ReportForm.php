<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 11:03:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-17 10:42:20
 * @Description: 
 */

namespace backend\modules\monitor\service\forms;

use common\models\monitor\project\house\Report;
use common\models\monitor\project\service\Report as ServiceReport;
use Yii;
use yii\base\Model;

/**
 * Class MemberForm
 * @package backend\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class ReportForm extends Model
{
    public $file;
    public $description;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['file'], 'safe'],
            [['description'], 'string', 'max' => 140],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'file' => '文件',
            'description' => '说明',
        ];
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save($id)
    {

        $transaction = Yii::$app->db->beginTransaction();
        try {
            $_model = ServiceReport::findOne($id);
            $model = $_model['report_id']
                ? Report::findOne($_model['report_id'])
                : new Report();
            $model->file = $this->file;
            $model->pid = $_model->house_id;
            $model->description = $this->description;
            if($model->save()){
                $_model->report_id = $model->attributes['id'];
                $_model->description = $model->description;
                $_model->save();
            }
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
        return true;
    }
}
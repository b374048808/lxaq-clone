<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 11:03:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-04-30 13:35:46
 * @Description: 
 */

namespace backend\modules\monitor\project\forms;

use common\enums\PointEnum;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\models\monitor\project\log\AngleLog;

/**
 * Class MemberForm
 * @package backend\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class StateForm extends Model
{

    public $type;
    public $pid;
    
    public $state;
    public $remark;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['state'], 'integer'],
            [['remark'], 'string', 'max' => 1000],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'state' => '状态',
            'remark' => '备注',
        ];
    }

     /**
     * 加载默认数据
     */
    public function loadData()
    {
        $modelClass = PointEnum::getModel($this->type);
        $model = $modelClass::findOne($this->pid);
        $this->state = $model->state;
    }



    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();

        try {
            $modelClass = PointEnum::getModel($this->type);
            $model = $modelClass::findOne($this->pid);
            $model->state = $this->state;
            $model->remark = $this->remark;

            if (!$model->save()) {
                $this->addErrors($model->getErrors());
                throw new NotFoundHttpException('用户编辑错误');
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
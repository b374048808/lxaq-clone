<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 11:03:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-13 09:56:22
 * @Description: 
 */

namespace backend\modules\monitor\project\forms;

use common\enums\PointEnum;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Class MemberForm
 * @package backend\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class EditAllForm extends Model
{

    public $value;  //值

    public $number; //加减
    
    public $warn;   //数据报警

    public $type;   //数据类型

    public $time;   //时间

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['number','value'], 'number'],
            [['type', 'warn', 'time'],'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'number' => '加减',
            'value' => '值',
            'type' => '类型',
            'warn' => '报警',
            'time' => '上传时间',
        ];
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
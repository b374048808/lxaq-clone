<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-16 09:49:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 10:17:23
 * @Description: 
 */

namespace backend\modules\monitor\project\forms;

use common\enums\ValueTypeEnum;
use common\models\monitor\project\point\Value;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Class LoginForm
 * @package backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ValueRandForm extends Model
{
    public $min_value;
    public $max_value;
    public $pid;
    public $type = ValueTypeEnum::AUTOMATIC;
    public $start_time;
    public $end_time;
    public $rand_time;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['min_value','max_value','rand_time'], 'required'],
            [['pid','rand_time','type'], 'integer'],
            [['start_time','end_time'],'safe'],
            [['min_value','max_value'], 'double']
        ];
    }

    public function attributeLabels()
    {
        return [
            'min_value' 	=> '最小值',
            'max_value' 	=> '最大值',
            'start_time'	=> '开始时间',
            'end_time'	=> '结束时间',
            'rand_time' => '间隔时间',
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
            $model = new Value();
            // 根据起始时间和结束时间生成数据
            for ($i=$this->start_time; $i < $this->end_time ; $i+= $this->rand_time*3600) { 
                $_model = clone $model;
                $_model->value = rand($this->min_value*10000,$this->max_value*10000)/10000;
                $_model->event_time = $i;
                $_model->type = $this->type;
                $_model->pid = $this->pid;
                if (!$_model->save()) {
                    $this->addErrors($model->getErrors());
                    throw new NotFoundHttpException('数据生成错误');
                }
            }

            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }


}
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 11:03:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-19 16:38:01
 * @Description: 
 */

namespace backend\modules\monitor\project\forms;

use common\enums\PointEnum;
use common\models\monitor\project\Point;
use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;

/**
 * Class MemberForm
 * @package backend\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class BatchPointForm extends Model
{

    public $pid;
    public $points;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['pid'],'integer'],
            [['points'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'pid' => '房屋',
            'points' => '多个监测点',
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
            $model = new Point();
            foreach ($this->points as $key => $value) {
                $_model = clone $model;
                $value['pid'] = $this->pid;
                if ($_model->load($value,'')) {
                    $model->save();
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
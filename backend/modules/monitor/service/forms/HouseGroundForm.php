<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 11:03:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-08 10:07:54
 * @Description: 
 */

namespace backend\modules\monitor\service\forms;

use Yii;
use yii\base\Model;

/**
 * Class MemberForm
 * @package backend\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class HouseGroundForm extends Model
{
    public $ground_id;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['ground_id'], 'required'],
            [['ground_id'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'ground_id' => '组别',
        ];
    }
}
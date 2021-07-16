<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 15:24:49
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 15:50:08
 * @Description: 
 */

namespace backend\modules\sim\renewal\forms;

use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class RenewalForm extends Model
{
    public $number;
    public $unit;
    public $description;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['number','unit'], 'required'],
            [['number'],'integer'],
            [['description'], 'string', 'max' => 140],
        ];
    }

    public function attributeLabels()
    {
        return [
            'number' 	=> '时间',
            'unit'      => '单位',
            'description'   => '备注'
        ];
    }

}
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 11:03:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-17 10:51:43
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
class AddHouseForm extends Model
{
    public $file;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['file'], 'required'],
            [['file'], 'safe'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'file' => '文件',
        ];
    }
}
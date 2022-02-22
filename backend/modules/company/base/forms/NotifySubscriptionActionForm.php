<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-12 10:32:25
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 09:40:12
 * @Description: 
 */

namespace backend\modules\company\base\forms;

use yii\base\Model;

/**
 * Class NotifySubscriptionActionForm
 * @package backend\modules\base\forms
 * @author jianyan74 <751393839@qq.com>
 */
class NotifySubscriptionActionForm extends Model
{
    public $sys;
    public $dingtalk;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sys', 'dingtalk'], 'each', 'rule' => ['integer']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'sys' => '系统',
            'dingtalk' => '钉钉',
        ];
    }
}

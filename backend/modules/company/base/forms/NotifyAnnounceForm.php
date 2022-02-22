<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-12 10:32:25
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 09:40:01
 * @Description: 
 */

namespace backend\modules\company\base\forms;

use common\models\worker\Notify;

/**
 * Class NotifyAnnounceForm
 * @package backend\modules\base\forms
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyAnnounceForm extends Notify
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'content'], 'required'],
            [['content'], 'string'],
            [['title'], 'string', 'max' => 150],
        ];
    }
}
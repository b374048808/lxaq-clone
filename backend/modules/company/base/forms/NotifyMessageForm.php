<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-12 10:32:25
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 09:49:20
 * @Description: 
 */

namespace backend\modules\company\base\forms;

use Yii;
use yii\base\Model;

/**
 * Class NotifyMessageForm
 * @package backend\modules\base\forms
 * @author jianyan74 <751393839@qq.com>
 */
class NotifyMessageForm extends Model
{
    public $content;

    public $toManagerId;

    public $data;

    public function init()
    {
        $this->data = Yii::$app->services->backendWorker->getMap();
        unset($this->data[Yii::$app->user->id]);

        parent::init();
    }

    public function rules()
    {
        return [
            [['content', 'toManagerId'], 'required'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'content' => '内容',
            'toManagerId' => '发送对象',
        ];
    }
}
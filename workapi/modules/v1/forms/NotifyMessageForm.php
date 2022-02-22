<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 10:44:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 10:45:30
 * @Description: 
 */

namespace workapi\modules\v1\forms;

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
        unset($this->data[Yii::$app->user->identity->member_id]);

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
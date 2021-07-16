<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:36:31
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 10:39:22
 * @Description: 
 */

namespace backend\modules\sim\vlist;

use Yii;

/**
 * iot module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\sim\vlist\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // Yii::$app->services->merchant->addId(0);
        // custom initialization code goes here
    }
}

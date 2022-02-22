<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:40:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-22 21:02:38
 * @Description: 
 */

namespace datav\modules\v1;

/**
 * v1 module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'datav\modules\v1\controllers';

    /**
     * {@inheritdoc}
     */
    public function init()
    {
        parent::init();

        // custom initialization code goes here
    }
}

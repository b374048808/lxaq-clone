<?php

namespace backend\modules\console\huawei;

use Yii;

/**
 * iot module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\console\huawei\controllers';

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

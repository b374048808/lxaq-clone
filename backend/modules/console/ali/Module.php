<?php

namespace backend\modules\console\ali;

use Yii;

/**
 * iot module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * {@inheritdoc}
     */
    public $controllerNamespace = 'backend\modules\console\ali\controllers';

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

<?php

namespace addons\RfMonitor\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfMonitor\frontend\controllers
 */
class BaseController extends AddonsController
{
    /**
    * @var string
    */
    public $layout = "@addons/RfMonitor/frontend/views/layouts/main";
}
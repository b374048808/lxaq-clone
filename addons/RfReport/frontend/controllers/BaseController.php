<?php

namespace addons\RfReport\frontend\controllers;

use Yii;
use common\controllers\AddonsController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package addons\RfReport\frontend\controllers
 */
class BaseController extends AddonsController
{
    /**
    * @var string
    */
    public $layout = "@addons/RfReport/frontend/views/layouts/main";
}
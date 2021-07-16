<?php
/*
 * @author: xjie<374048808@qq.com>
 * @date: Do not edit
 * @lastEditors: xjie<374048808@qq.com>
 * @lastEditTime: Do not edit
 * @description: 
 */

namespace backend\modules\member\base\controllers;

use common\enums\AppEnum;
use common\models\rbac\AuthItem;
use common\traits\AuthItemTrait;
use backend\controllers\BaseController;


class AuthItemController extends BaseController
{
    use AuthItemTrait;

    /**
     * @var AuthItem
     */
    public $modelClass = AuthItem::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::API;

    /**
     * 渲染视图前缀
     *
     * @var string
     */
    public $viewPrefix = '@backend/modules/member/base/views/auth-item/';
}
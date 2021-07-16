<?php
/*
 * @Author: Xjie
 * @Date: 2021-04-28 14:04:00
 * @LastEditors: Xjie
 * @LastEditTime: 2021-04-30 08:42:00
 * @Description: 
 */

namespace backend\modules\member\base\controllers;

use common\traits\AuthRoleTrait;
use common\models\rbac\AuthRole;
use common\enums\AppEnum;
use backend\controllers\BaseController;


class AuthRoleController extends BaseController
{
    use AuthRoleTrait;

    /**
     * @var AuthRole
     */
    public $modelClass = AuthRole::class;

    /**
     * 默认应用
     *
     * @var string
     */
    public $appId = AppEnum::API;

    /**
     * 权限来源
     *
     * false:所有权限，true：当前角色
     *
     * @var bool
     */
    public $sourceAuthChild = true;

    /**
     * 渲染视图前缀
     *
     * @var string
     */
    public $viewPrefix = '@backend/modules/member/base/views/auth-role/';

}
<?php

return [

    // ----------------------- 菜单配置 ----------------------- //
    'config' => [
        // 菜单配置
        'menu' => [
            'location' => 'addons', // default:系统顶部菜单;addons:应用中心菜单
            'icon' => 'fa fa-puzzle-piece',
        ],
        // 子模块配置
        'modules' => [
        ],
    ],

    // ----------------------- 快捷入口 ----------------------- //

    'cover' => [

    ],

    // ----------------------- 菜单配置 ----------------------- //

    'menu' => [
        [
            'title' => '文档管理',
            'route' => 'doc/index',
            'icon' => '',
            'params' => [

            ],
        ],
        [
            'title' => '文档分类',
            'route' => 'cate/index',
            'icon' => '',
            'params' => [

            ],
        ],
        [
            'title' => '模板管理',
            'route' => 'model/index',
            'icon' => '',
            'params' => [

            ],
        ],
        [
            'title' => '字符管理',
            'route' => 'char/index',
            'icon' => '',
            'params' => [

            ],
        ],
    ],

    // ----------------------- 权限配置 ----------------------- //

    'authItem' => [
        [
            'title' => '所有权限',
            'name' => '*',
        ],
    ],
];
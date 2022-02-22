<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'workapi',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'workapi\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [ // 版本1
            'class' => 'workapi\modules\v1\Module',
        ],
        'v2' => [ // 版本2
            'class' => 'workapi\modules\v2\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-api',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'as beforeSend' => 'workapi\behaviors\BeforeSend',
        ],
        'user' => [
            'identityClass' => 'common\models\workapi\AccessToken',
            'enableAutoLogin' => true,
            'enableSession' => false, // 显示一个HTTP 403 错误而不是跳转到登录界面
            'loginUrl' => null,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                    'logFile' => '@runtime/logs/' . date('Y-m/d') . '.log',
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'message/error',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // 美化Url,默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            'enablePrettyUrl' => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。启用容易出错
            // 注意:如果不需要严格解析路由请直接删除或注释此行代码
            'enableStrictParsing' => true,
            // 是否在URL中显示入口脚本。是对美化功能的进一步补充。
            'showScriptName' => false,
            // 指定续接在URL后面的一个后缀，如 .html 之类的。仅在 enablePrettyUrl 启用时有效。
            'suffix' => '',
            'rules' => [
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => [
                        /**
                         * 默认登录测试控制器(Post)
                         * http://当前域名/api/v1/site/login
                         */
                        // 'sign-secret-key',
                        // 版本1
                        'v1/default', // 默认测试入口
                        'v1/site',
                        'v1/home',  //首页
                        'v1/mini-program',
                        'v1/bank-account',
                        'v1/common/provinces',
                        'v1/member/member',
                        'v1/member/auth',
                        'v1/member/notify',
                        'v1/member/notify-announce',
                        'v1/member/notify-remind',
                        'v1/member/notify-message',
                        'v1/project/house', //房屋
                        'v1/project/value', //房屋
                        'v1/project/point', //房屋
                        'v1/project/report', //报告
                        'v1/project/report-member', //报告
                        'v1/monitor/item', //任务
                        'v1/monitor/item-audit', //任务
                        'v1/monitor/item-contact', //合同
                        'v1/monitor/item-house', //关联房屋
                        'v1/monitor/item-config', //项目额外配置
                        'v1/monitor/item-number', //项目额外配置
                        'v1/monitor/service', //任务
                        'v1/monitor/service-map', //任务详情
                        'v1/monitor/service-house', //任务房屋列表
                        'v1/monitor/service-audit', //任务审核
                        'v1/console/ali-device', //房屋
                        'v1/console/hw-device', //华为设备
                        'v1/console/ali-device', //阿里设备
                    ],
                    'pluralize' => false, // 是否启用复数形式，注意index的复数indices，开启后不直观
                    'extraPatterns' => [
                        'POST login' => 'login', // 登录获取token
                        'POST logout' => 'logout', // 退出登录
                        'POST refresh' => 'refresh', // 重置token
                        'POST sms-code' => 'sms-code', // 获取验证码
                        'POST register' => 'register', // 注册
                        'POST up-pwd' => 'up-pwd', // 重置密码
                        'POST up-date' => 'up-date', //更新数据
                        'POST house-map' => 'house-map', //更新数据
                        'POST edit' => 'edit', //编辑数据
                        'POST verify' => 'verify', //审核
                        'POST steps' => 'steps', //步骤变更
                        'POST get-directive' => 'get-directive', //发布命令
                        // 测试查询可删除 例如：http://www.rageframe.com/api/v1/default/search
                        'GET search' => 'search',
                        'GET qr-code' => 'qr-code', // 获取小程序码
                        'GET view' => 'view',
                        'GET real-all' => 'real-all',
                        'GET send' => 'send',   //提交
                        'GET default'   => 'default',   //编辑页面默认配置
                        'GET index' => 'index', //首页,
                        'GET value' => 'value', //首页,                        
                        'GET list' => 'list', //列表,  
                        'GET show' => 'show', //还原,  
                        'GET recycle' => 'recycle', //回收站,  
                        'GET wait-list' => 'wait-list',  //待审核
                        'GET steps-list' => 'steps-list', //还原,  
                        'GET rbac' => 'rbac', //权限配置, 
                        'POST onshub-message' => 'onshub-message',
                        'POST signature' => 'signature', //  
                        'PUT update'    => 'update',
                        'PUT audit'    => 'audit',  //审核
                        'DELETE delete'    => 'delete',
                        'DELETE delete-all'    => 'delete-all'

                    ]
                ],
                [
                    'class' => 'yii\rest\UrlRule',
                    'controller' => ['v1/file'],
                    'pluralize' => false,
                    'extraPatterns' => [
                        'POST images' => 'images', // 图片上传
                        'POST videos' => 'videos', // 视频上传
                        'POST voices' => 'voices', // 语音上传
                        'POST files' => 'files', // 文件上传
                        'POST base64' => 'base64', // base64上传
                        'POST merge' => 'merge', // 合并分片
                        'POST verify-md5' => 'verify-md5', // md5文件校验
                        'GET oss-accredit' => 'oss-accredit', // oss js 直传配置

                    ],
                ],
                [
                    'class' => 'workapi\rest\UrlRule',
                    'controller' => ['addons'],
                    'pluralize' => false,
                ],
            ]
        ],
    ],
    'as cors' => [
        'class' => \yii\filters\Cors::class,
    ],
    'params' => $params,
];

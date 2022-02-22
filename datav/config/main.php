<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'datav',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'datav\controllers',
    'bootstrap' => ['log'],
    'modules' => [
        'v1' => [ // 版本1
            'class' => 'datav\modules\v1\Module',
        ],
        'v2' => [ // 版本2
            'class' => 'datav\modules\v2\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-datav',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
                'text/json' => 'yii\web\JsonParser',
            ]
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'as beforeSend' => 'datav\behaviors\BeforeSend',
        ],
        'user' => [
            'identityClass' => 'common\models\datav\AccessToken',
            'enableAutoLogin' => true,
            'enableSession' => false,// 显示一个HTTP 403 错误而不是跳转到登录界面
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
            // 'errorAction' => 'message/error',
        ],
        'urlManager' => [
            'class' => 'yii\web\UrlManager',
            // 美化Url,默认不启用。但实际使用中，特别是产品环境，一般都会启用。
            'enablePrettyUrl' => true,
            // 是否启用严格解析，如启用严格解析，要求当前请求应至少匹配1个路由规则，
            // 否则认为是无效路由。
            // 这个选项仅在 enablePrettyUrl 启用后才有效。启用容易出错
            // 注意:如果不需要严格解析路由请直接删除或注释此行代码
            // 'enableStrictParsing' => true,
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
                         * http://当前域名/datav/v1/site/login
                         */
                        // 'sign-secret-key',
                        // 版本1
                        'v1/default',// 默认测试入口
                        'v1/site',
                        'v1/mini-program',
                        'v1/pay',
                        'v1/common/provinces',
                        'v1/member/member',
                        'v1/member/address',
                        'v1/member/invoice',
                        'v1/member/auth',
                        'v1/member/bank-account',
                        // 客户端
                        'v1/project/house', //房屋
                        'v1/project/house/view',
                        'v1/project/house/chart',   //房屋数据图表
                        'v1/project/warn', //报警记录
                        'v1/project/point', //房屋监测点
                        'v1/project/point/view',
                        'v1/project/ground-map',   //分组
                        'v1/project/value',   //数据
                        
                        'v1/iot/device' //物联网设备
                    ],
                    'pluralize' => false, // 是否启用复数形式，注意index的复数indices，开启后不直观
                    'extraPatterns' => [
                        'POST login' => 'login', // 登录获取token
                        'POST logout' => 'logout', // 退出登录
                        'POST refresh' => 'refresh', // 重置token
                        'POST sms-code' => 'sms-code', // 获取验证码
                        'POST register' => 'register', // 注册
                        'POST up-pwd' => 'up-pwd', // 重置密码
                        // 测试查询可删除 例如：http://www.rageframe.com/datav/v1/default/search
                        'GET index' => 'index', //首页
                        'GET view' => 'view',   //详情
                        'GET chart' => 'chart',   //详情
                        'GET list' => 'list',   //详情
                        'GET house-list' => 'house-list',   //详情
                        'GET qr-code' => 'qr-code', // 获取小程序码
                        'GET dele' => 'dele',   //删除
                        'GET delete-all' => 'delete-all',   //批量删除
                        'POST edit' => 'edit', // 编辑
                        'POST create' => 'create', // 创建'
                        'POST value' => 'value', // 接受数据
                        'GET area-map' => 'area-map', // 房屋地区分类
                        'GET get-day-char' => 'get-day-char' // 当天数据
                        
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
                    'class' => 'datav\rest\UrlRule',
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

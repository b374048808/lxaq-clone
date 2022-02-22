<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'defaultRoute' => 'main', // 默认控制器
    'bootstrap' => ['log'],
    'modules' => [
        /** ------ 公用模块 ------ **/
        'common' => [
            'class' => 'backend\modules\common\Module',
        ],
        /** ------ 基础模块 ------ **/
        'base' => [
            'class' => 'backend\modules\base\Module',
        ],
        /** ------ 会员模块 ------ **/
        'member' => [
            'class' => 'backend\modules\member\Module',
        ],
        /** ------ 会员权限模块 ------ **/
        'member-base' => [
            'class' => 'backend\modules\member\base\Module',
        ],
        /** ------ 员工模块 ------ **/
        'worker' => [
            'class' => 'backend\modules\company\worker\Module',
        ],
        /** ------ 公司基础 ------ **/
        'company-base' => [
            'class' => 'backend\modules\company\base\Module',
        ],
        'company-service' => [
            'class' => 'backend\modules\company\service\Module',
        ],
        /** ------ 物联网模块 ------ **/
        'console-ali' => [
            'class' => 'backend\modules\console\ali\Module',
        ],
        /** ------ 物联网模块 ------ **/
        'console-huawei' => [
            'class' => 'backend\modules\console\huawei\Module',
        ],
        /** ------ 物联网模块 ------ **/
        'console-lk' => [
            'class' => 'backend\modules\console\lk\Module',
        ],
        /** ------ 项目模块 ------ **/
        'monitor-project' => [
            'class' => 'backend\modules\monitor\project\Module',
        ],
        /** ------ 项目模块 ------ **/
        'monitor-rule' => [
            'class' => 'backend\modules\monitor\rule\Module',
        ],
        /** ------ 项目首页 ------ **/
        'monitor-main' => [
            'class' => 'backend\modules\monitor\main\Module',
        ],
        /** ------ 项目模块 ------ **/
        'monitor-lk' => [
            'class' => 'backend\modules\monitor\lk\Module',
        ],
        'monitor-log' => [
            'class' => 'backend\modules\monitor\log\Module',
        ],
        'monitor-data' => [
            'class' => 'backend\modules\monitor\data\Module',
        ],
        'monitor-service' => [
            'class' => 'backend\modules\monitor\service\Module',
        ],
        /** ------ 项目模块 ------ **/
        'monitor-create' => [
            'class' => 'backend\modules\monitor\create\Module',
        ],
        /** ------ 物联卡 ------ **/
        'sim-list' => [
            'class' => 'backend\modules\sim\vlist\Module',
        ],
        'sim-renewal' => [
            'class' => 'backend\modules\sim\renewal\Module',
        ],
        /** ------ oauth2 ------ **/
        'oauth2' => [
            'class' => 'backend\modules\oauth2\Module',
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',
        ],
        'user' => [
            'identityClass' => 'common\models\backend\Member',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'idParam' => '__backend',
            'on afterLogin' => function($event) {
                Yii::$app->services->backendMember->lastLogin($event->identity);
            },
        ],
        // 'view'=>[
        //     'theme' => [
        //         'basePath' => '@backend/themes/defaults',
        //         'baseUrl' => '@web/themes/defaults',
        //         'pathMap' => [
        //             '@backend/views' => '@backend/themes/defaults'
        //         ],
        //     ]
        // ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
            'timeout' => 86400,
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
            'errorAction' => 'site/error',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
            ],
        ],
        'assetManager' => [
            // 'linkAssets' => true,
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'js' => [],
                    'sourcePath' => null,
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'css' => [],  // 去除 bootstrap.css
                    'sourcePath' => null,
                ],
                'yii\bootstrap\BootstrapPluginAsset' => [
                    'js' => [],  // 去除 bootstrap.js
                    'sourcePath' => null,
                ],
            ],
        ],
        'response' => [
            'class' => 'yii\web\Response',
            'on beforeSend' => function($event) {
                Yii::$app->services->log->record($event->sender);
            },
        ],
    ],
    'container' => [
        'definitions' => [
            'yii\widgets\LinkPager' => [
                'nextPageLabel' => '<i class="icon ion-ios-arrow-right"></i>',
                'prevPageLabel' => '<i class="icon ion-ios-arrow-left"></i>',
                'lastPageLabel' => '<i class="icon ion-ios-arrow-right"></i><i class="icon ion-ios-arrow-right"></i>',
                'firstPageLabel' => '<i class="icon ion-ios-arrow-left"></i><i class="icon ion-ios-arrow-left"></i>',
            ]
        ],
        'singletons' => [
            // 依赖注入容器单例配置
        ]
    ],
    'controllerMap' => [
        'file' => 'common\controllers\FileBaseController', // 文件上传公共控制器
        'ueditor' => 'common\widgets\ueditor\UeditorController', // 百度编辑器
        'provinces' => 'common\widgets\provinces\ProvincesController', // 省市区
        'select-map' => 'common\widgets\selectmap\MapController', // 经纬度选择
        'cropper' => 'common\widgets\cropper\CropperController', // 图片裁剪
        'notify' => 'backend\widgets\notify\NotifyController', // 消息
        'wnotify' => 'backend\widgets\wnotify\NotifyController', // 员工消息
        'monitor-notify' => 'backend\widgets\monitornotify\NotifyController', // 消息
    ],
    'params' => $params,
];

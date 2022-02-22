<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-22 20:34:28
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-22 21:20:17
 * @Description: 
 */

namespace datav\assets;

use yii\web\AssetBundle;

/**
 * Class AppAsset
 * @package html5\assets
 * @author jianyan74 <751393839@qq.com>
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        // 'css/site.css',
    ];
    public $js = [
    ];
    public $depends = [
        // 'yii\web\YiiAsset',
        // 'yii\bootstrap\BootstrapAsset',
    ];
}

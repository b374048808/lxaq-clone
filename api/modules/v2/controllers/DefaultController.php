<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-09 14:29:44
 * @Description: 
 */

namespace api\modules\v2\controllers;

use Yii;
use api\controllers\OnAuthController;

/**
 * 默认控制器
 *
 * Class DefaultController
 * @package api\modules\v2\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class DefaultController extends OnAuthController
{
    public $modelClass = '';

    /**
     * 不用进行登录验证的方法
     * 例如： ['index', 'update', 'create', 'view', 'delete']
     * 默认全部需要验证
     *
     * @var array
     */
    protected $authOptional = ['index'];

    /**
     * @return string|\yii\data\ActiveDataProvider
     */
    public function actionIndex()
    {
        return 0 < true ? 1 : true;
    }
}
function send_post($url, $post_data)
{
    // $postdata = http_build_query($post_data);
    // $options = array(
    //     'http' => array(
    //         'method' => 'POST',
    //         'header' => 'Content-type:application/x-www-form-urlencoded',
    //         'content' => $postdata,
    //         'timeout' => 15 * 60 // 超时时间（单位:s）
    //     )
    // );
    // $context = stream_context_create($options);
    // $result = file_get_contents($url, false, $context);

    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);

    // POST数据

    curl_setopt($ch, CURLOPT_POST, 1);

    // 把post的变量加上

    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);

    $output = curl_exec($ch);

    curl_close($ch);

    return $output;

    var_dump($result);
    return $result;
}

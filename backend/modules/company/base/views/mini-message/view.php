<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-30 10:55:30
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 11:00:00
 * @Description: 
 */

use common\helpers\DebrisHelper;
use common\helpers\Html;
use common\models\worker\Worker;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <td>接收用户</td>
                <td><?= Worker::getRealname(Html::encode($model['message']['member_id'])) ?></td>
            </tr>
            <tr>
                <td style="min-width: 100px">发送数据</td>
                <td style="max-width: 700px">
                    <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['message_data'])) ?>
                </td>
            </tr>
            <tr>
                <td>行为</td>
                <td><?= Html::encode($model['message']['action']) ?></td>
            </tr>
            <tr>
                <td>类型</td>
                <td><?= Html::encode($model['message']['target_type']) ?></td>
            </tr>
            <tr>
                <td>对应ID</td>
                <td><?= Html::encode($model['message']['target_id']) ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
<?php

use common\helpers\DebrisHelper;
use common\helpers\Html;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <td>设备</td>
                <td><?= Html::encode($model['pid']) ?></td>
            </tr>
            <tr>
                <td>消息ID</td>
                <td><?= Html::encode($model['message_id']) ?></td>
            </tr>
            <tr>
                <td>质量</td>
                <td><?= Html::encode($model['qos']) ?></td>
            </tr>
            <tr>
                <td>目的</td>
                <td><?= Html::encode($model['destination']) ?></td>
            </tr>
            <tr>
                <td>主题</td>
                <td><?= Html::encode($model['topic']) ?></td>
            </tr>
            <tr>
                <td>订阅</td>
                <td><?= Html::encode($model['subscription']) ?></td>
            </tr>
            <tr>
                <td>十六进制值</td>
                <td id="value-body"><?= Html::encode($model['body']) ?></td>
            </tr>
            <tr>
                <td>ASCII</td>
                <td id="value-body"><?= Html::encode(hex2bin($model['body'])) ?></td>
            </tr>
            <tr>
                <td style="min-width: 100px">整体</td>
                <td style="max-width: 700px">
                    <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['message'])) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>

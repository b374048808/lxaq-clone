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
            <td>消息类型</td>
            <td><?= Html::encode($model['notifyType']) ?></td>
        </tr>
        <tr>
            <td>设备ID</td>
            <td><?= Html::encode($model['deviceId']) ?></td>
        </tr>
        <tr>
            <td>网关</td>
            <td><?= Html::encode($model['gatewayId']) ?></td>
        </tr>
        <tr>
            <td style="min-width: 100px">内容</td>
            <td style="max-width: 700px">
                <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['value'])) ?>
            </td>
        </tr>
        <tr>
            <td style="min-width: 100px">整体</td>
            <td style="max-width: 700px">
                <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['services'])) ?>
            </td>
        </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
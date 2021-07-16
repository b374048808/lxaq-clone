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
                <td>设备ID</td>
                <td><?= Html::encode($model->user->username) ?></td>
            </tr>
            <tr>
                <td>设备ID</td>
                <td><?= Html::encode($model['device']['device_id']) ?></td>
            </tr>
            <tr>
                <td>命令</td>
                <td><?= Html::encode($model['directive']['title']) ?></td>
            </tr>
            <tr>
                <td style="min-width: 100px">参数</td>
                <td style="max-width: 700px">
                    <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['params'])) ?>
                </td>
            </tr>
            <tr>
                <td>IP地址</td>
                <td><?= Html::encode($model['ip']) ?></td>
            </tr>
            <tr>
                <td>内容</td>
                <td><?= Html::encode($model['content']) ?></td>
            </tr>
            <tr>
                <td style="min-width: 100px">整体</td>
                <td style="max-width: 700px">
                    <?php Yii::$app->debris->p(DebrisHelper::htmlEncode($model['results'])) ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
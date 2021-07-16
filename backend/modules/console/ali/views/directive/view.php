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
                <td>标题</td>
                <td><?= Html::encode($model['title']) ?></td>
            </tr>
            <tr>
                <td>命令</td>
                <td><?= Html::encode($model['content']) ?></td>
            </tr>

        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
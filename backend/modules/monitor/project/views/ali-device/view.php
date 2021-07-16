<?php

use common\enums\AxisEnum;
use common\enums\NewsEnum;
use common\helpers\DebrisHelper;
use common\helpers\Html;
use common\helpers\ImageHelper;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <td>设备名称</td>
                <td><?= Html::encode($model['device']['device_name']) ?></td>
            </tr>
            <tr>
                <td>设备编号</td>
                <td>
                    <?= Html::a(Html::encode($model['device']['number']), ['/console-ali/device/view', 'id' => $model['device_id']], $options = ['class' => 'dm-bold openContab']) ?>
                </td>
            </tr>
            <tr>
                <td>安装图片</td>
                <td><?= ImageHelper::fancyBoxs($model['covers']) ?></td>
            </tr>
            <tr>
                <td>安装时间</td>
                <td><?= Html::encode(date('Y-m-d H:i:s', $model['install_time'])) ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
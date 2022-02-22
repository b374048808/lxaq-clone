<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-01 11:55:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-10 15:02:58
 * @Description: 
 */

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
                <td>上传</td>
                <td><?= Html::encode($model['member']['realname']) ?></td>
            </tr>
            <tr>
                <td>经办人</td>
                <td><?= Html::encode($model['manager']['realname']) ?></td>
            </tr>
            <tr>
                <td>金额</td>
                <td><?= Html::encode($model['money']) ?></td>
            </tr>
            <tr>
                <td>附件</td>
                <td>
                    <?php foreach ($model->file ?: [] as $key => $v) : ?>
                        <?= ImageHelper::fancyBox($v); ?>
                    <?php endforeach; ?>
                </td>
            </tr>
            
            <tr>
                <td>签约</td>
                <td><?= Html::encode(date('Y-m-d H:i:s',$model['event_time'])) ?></td>
            </tr>
            <tr>
                <td>上传时间</td>
                <td><?= Html::encode(date('Y-m-d H:i:s',$model['created_at'])) ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>

<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-01 11:55:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 11:38:13
 * @Description: 
 */

use common\enums\VerifyEnum;
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
                <td>上传人员</td>
                <td><?= Html::encode($model['user']['realname']) ?></td>
            </tr>
            <tr>
                <td>文件名</td>
                <td><?= Html::encode($model['file_name']) ?></td>
            </tr>
            <tr>
                <td>文件</td>
                <td>
                    <a href="<?= $model['file'] ?>" download="附件">点击下载</a>
                </td>
            </tr>
            <tr>
                <td>结论</td>
                <td><?= Html::encode($model['description']) ?></td>
            </tr>
        </tbody>

    </table>
    <h4>审核人员</h4>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <?php foreach ($model->verifyMemberList?: [] as $key => $value) : ?>
                <tr>
                    <td><?= Html::encode($value['member']['realname']) ?></td>
                    <td><?= Html::encode(VerifyEnum::getValue($value['verify'])) ?></td>
                    <td><?= Html::encode(date('Y-m-d H:i:s',$value['updated_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <h4>日志</h4>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <?php foreach ($model->log ?: [] as $key => $value) : ?>
                <tr>
                    <td><?= Html::encode($value['remark']) ?></td>
                    <td><?= Html::encode(date('Y-m-d', $value['created_at'])) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 13:49:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-15 16:33:43
 * @Description: 
 */

use common\enums\ValueTypeEnum;
use common\enums\PointEnum;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <?= $this->render('view/' . PointEnum::getSymbolValue($model['parent']['type']), [
                'model' => $model,
            ]) ?>
            <tr>
                <td>数据类型</td>
                <td><?= ValueTypeEnum::getValue($model['type']) ?></td>
            </tr>
            <tr>
                <td>设备时间</td>
                <td><?= date('Y-m-d H:i:s', $model['event_time']) ?></td>
            </tr>
            <tr>
                <td>上传时间</td>
                <td><?= date('Y-m-d H:i:s', $model['created_at']) ?></td>
            </tr>
            <tr>
                <td>更新时间</td>
                <td><?= date('Y-m-d H:i:s', $model['updated_at']) ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
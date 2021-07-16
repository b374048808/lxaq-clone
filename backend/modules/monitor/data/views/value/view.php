<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 14:50:10
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 14:57:57
 * @Description: 
 */

use common\enums\ValueTypeEnum;
use common\enums\ValueStateEnum;
use common\enums\WarnEnum;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">数据记录</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <th>上传时间</th>
                <th>数据类型</th>
                <th>数据</th>
                <th>状态</th>
                <th>预警</th>
            </tr>
            <tr>                
                <td><?= date('Y-m-d H:i:s', $model['event_time']) ?></td>
                <td><?= ValueTypeEnum::getValue($model['type']) ?></td>
                <td><?= $model['value'] ?></td>
                <td><?= ValueStateEnum::getValue($model['state']) ?></td>
                <td><?= WarnEnum::$spanlistExplain[$model['warn']] ?></td>
            </tr>
        </tbody>
    </table>
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
            <th>人员</th>
                <th>修改时间</th>
                <th>数据类型</th>
                <th>上传时间</th>
                <th>数据</th>
                <th>状态</th>
                <th>预警</th>
            </tr>
            <?php foreach ($log as $key => $value): ?>
            <tr>
            <td><?= $value['user']['username'] ?></td>
                <td><?= date('Y-m-d H:i:s', $value['created_at']) ?></td>
                <td><?= ValueTypeEnum::getValue($value['type']) ?></td>
                <td><?= date('Y-m-d H:i:s', $value['event_time']) ?></td>
                <td><?= $value['value'] ?></td>
                <td><?= ValueStateEnum::getValue($value['state']) ?></td>
                <td><?= WarnEnum::$spanlistExplain[$value['warn']] ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 14:50:10
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-14 15:59:42
 * @Description: 
 */

use common\enums\monitor\SubscriptionActionEnum;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">数据记录</h4>
</div>
<div class="modal-body">
<table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <td>时间</td>
                <td><?= date('Y-m-d H:i:s', $model['created_at']) ?></td>
            </tr>
            <tr>
                <td>数据类型</td>
                <td><?= SubscriptionActionEnum::$listExplain[$model['action']] ?></td>
            </tr>
            <tr>
                <td>内容</td>
                <td><?= $model['content'] ?></td>
            </tr>
            
        </tbody>
    </table>
    
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 14:42:16
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 14:45:22
 * @Description: 
 */

use common\enums\WarnEnum;
use common\helpers\DebrisHelper;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">原始数据</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <td style="min-width: 100px">当前值</td>
                <td style="max-width: 700px">
                    <?= $model['onValue']['value'] ?>
                </td>
            </tr>
            <tr>
                <td style="min-width: 100px">时间</td>
                <td style="max-width: 700px">
                    <?= date('Y-m-d H:i:s',$model['onValue']['event_time']) ?>
                </td>
            </tr>
            <tr>
                <td style="min-width: 100px">预警等级</td>
                <td style="max-width: 700px">
                    <?= WarnEnum::$spanlistExplain[$model['onValue']['warn']] ?>
                </td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>

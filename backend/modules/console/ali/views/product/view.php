<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-12 15:32:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-24 10:12:19
 * @Description: 
 */

use common\helpers\Url;

$this->title = $model['name'];
$this->params['breadcrumbs'][] = ['label' => '产品管理', 'url' => Url::to(['index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<style>
    .panel-delete-right {
        float: right;
        font-size: 12px;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">概况</a></li>
                <li><a href="<?= Url::to(['/console-ali/directive/index', 'pid' => $model['id']]) ?>">命令</a></li>
            </ul>
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <td>产品名称</td>
                        <td><?= $model->name ?></td>
                        <td>产品key</td>
                        <td><?= $model->product_key ?></td>
                    </tr>
                    <tr>
                        <td>产品类型</td>
                        <td><?= $model->type ?></td>
                        <td>产家名称</td>
                        <td><?= $model->producers ?></td>
                    </tr>
                </table>
            </div>
        </div>

    </div>
</div>
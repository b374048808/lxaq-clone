<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-21 11:05:36
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-30 09:13:54
 * @Description: 
 */

use common\helpers\Html;
use common\helpers\Url;

$this->title = '命令';
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => Url::to(['/console-huawei/device/index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['/console-huawei/device/view', 'id' => $model['id']], $schema = true) ?>">概况</a></li>
                <li><a href="<?= Url::to(['attr', 'id' => $model['id']]) ?>">属性</a></li>
                <li class="active"><a href="#">命令</a></li>
                <li class="pull-right">
                    <?= Html::linkButton(['/console-huawei/directive-log/index', 'id' => $model['id']], '<i class="icon ion-navicon"></i> 历史命令', [
                        'class' => 'btn btn-info btn-xs'
                    ]); ?>
                </li>
            </ul>
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <th>服务</th>
                        <th>操作</th>
                    </tr>
                    <?php foreach ($model['directive'] as $key => $value) : ?>
                        <tr>
                            <td><?= $value['title'] ?></td>
                            <td>
                                <?= Html::a('下发', ['/console-huawei/device/get-directive','id' => $model['id'],'directive_id' => $value['id']], $options = [
                                    'class' => 'purple',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal'
                                ]) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
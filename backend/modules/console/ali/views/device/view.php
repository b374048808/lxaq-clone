<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-22 10:21:59
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 10:39:57
 * @Description: 
 */

use common\helpers\Html;
use common\helpers\Url;

$this->title = isset($model['number']) ? $model['number'] : $model['device_name'];
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => Url::to(['/console-ali/device/index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">概况</a></li>
                <li><a href="<?= Url::to(['directive', 'id' => $model['id']]) ?>">命令</a></li>
                <li class="pull-right">
                    <?= Html::edit(['ajax-edit', 'id' => $model['id']], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <td>设备识别码</td>
                        <td><?= $model->number ?></td>
                        <td>初始值</td>
                        <td><?= $model['start_data'] ?>(设备实际数据为初始值减去当前值)</td>
                        <td>第三方IOT-ID</td>
                        <td><?= $model->device_id ?></td>
                    </tr>
                </table>
            </div>
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">关联使用情况</h3>
                </div>
                <div class="box-body">
                    <table class="table table-hover">
                        <tr>
                            <th>房屋</th>
                            <th>监测点</th>
                            <th>时间</th>
                            <th>数据</th>
                        </tr>
                        <?php foreach ($pointModel as $key => $value) : ?>
                            <tr>
                                <td><?= Html::a($value['house']['title'], ['/monitor-project/house/view', 'id' => $value['pid']], $options = []) ?></td>
                                <td><?= Html::a($value['title'], ['/monitor-project/point/view', 'id' => $value['id']], $options = []) ?></td>
                                <td><?= $value['newValue']['value'] ?></td>
                                <td><?= $value['newValue']['event_time']?date('Y-m-d H:i:s', $value['newValue']['event_time']):'--' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
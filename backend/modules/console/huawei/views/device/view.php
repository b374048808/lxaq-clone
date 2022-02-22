<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-14 10:44:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-18 15:32:46
 * @Description: 
 */

use common\helpers\Html;
use common\helpers\Url;

$this->title = '设备概况';
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => Url::to(['/console-huawei/device/index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<style>
    .table-responsive-col {
        border-right: 1px solid #f1f1f1;
        text-align: center;
    }

    div.table-responsive-col:after {
        border-right: 5px solid #f1f1f1;
    }

    .table-responsive-col span {
        color: #575D6C;
        font-size: 12px;
        margin-top: 10px;
    }

    .table-responsive-col p {
        color: #8A8E99;
        font-size: 12px;
        margin-top: 10px;
    }

    /* 加粗 */
    .dm-bold,
    .dm-title,
    .dm-title-child,
    .dm-layout h1,
    .dm-layout h2 {
        font-weight: bold;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">概况</a></li>
                <li><a href="<?= Url::to(['attr', 'id' => $model['id']]) ?>">属性</a></li>
                <li><a href="<?= Url::to(['directive', 'id' => $model['id']]) ?>">命令</a></li>
                <li class="pull-right">
                    <?= Html::edit(['ajax-edit', 'id' => $model['id']], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
            <div class="box-body">
                <div class="box-header">
                    <h3 class="box-title"><?= Html::switch($model['switch']) ?></h3>
                </div>
                <table class="table table-hover">
                    <tr>
                        <td>设备识别码</td>
                        <td><?= $model['number'] ?></td>
                        <td>第三方IOT-ID</td>
                        <td><?= $model['device_id'] ?></td>
                    </tr>
                    <tr>
                        <td>状态</td>
                        <td><?= $model['deviceStatus'] . ' ' . $model['deviceVoltage'] ?></td>
                        <td>最后上线时间</td>
                        <td><?= $model['last_time'] ? date('Y-m-d', $model['last_time']) : '' ?></td>
                    </tr>
                    <tr>
                        <td>卡号</td>
                        <td><?= $model['card'] ?></td>
                        <td>过期时间</td>
                        <td><?= $model['over_time'] ? date('Y-m-d', $model['over_time']) : '' ?></td>
                    </tr>
                    <tr>
                        <td>备注</td>
                        <td colspan="3"><?= $model['description'] ?></td>
                    </tr>
                </table>
            </div>
        </div>

        <div class="box">
            <div class="box-body">
                <div class="box-header">
                    <h3 class="box-title">属性</h3>
                    <div class="box-tools">
                        <!-- <?= Html::linkButton(['/console-huawei/value/index', 'pid' => $model['id']], '历史数据', ['class' => 'btn btn-white btn-sm openContab']) ?> -->
                        <?= Html::linkButton(['/console-huawei/value/index', 'pid' => $model['id']], '历史数据', ['class' => 'btn btn-white btn-sm']) ?>
                        <?= Html::linkButton(['attr', 'id' => $model['id']], '全部属性') ?>
                    </div>
                </div>
                <?php foreach ($model->newValue->value ?: [] as $key => $value) : ?>
                    <div class="col-md-2 col-sm-4 col-xs-6" style="padding:20px 10px">
                        <div class="table-responsive-col">
                            <?= Html::a($key, ['/console-huawei/value/chart', 'pid' => $model['id'], 'service' => $model->newValue->serviceType, 'type' => $key], $options = ['class' => 'dm-bold openContab']) ?>
                            <p class="dm-bold" style="font-size: 20px;margin-top: 10px" title="<?= $value ?>"><?= $value ?></p>
                            <<span><?= $model->newValue->serviceType ?></span>>
                                <p><?= date('Y-m-d H:i:s', $model->newValue->event_time) ?></p>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
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
                            <td><?= Html::a($value['house']['title'], ['/monitor-project/house/view', 'id' => $value['pid']], $options = [
                                    'class' => 'openContab'
                                ]) ?></td>
                            <td><?= Html::a($value['title'], ['/monitor-project/point/view', 'id' => $value['id']], $options = [
                                    'class' => 'openContab'
                                ]) ?></td>
                            <td><?= $value['newValue']['value'] ?></td>
                            <td><?= $value['newValue']['event_time'] ? date('Y-m-d H:i:s', $value['newValue']['event_time']) : '--' ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
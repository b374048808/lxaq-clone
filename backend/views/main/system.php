<?php

use common\enums\monitor\SubscriptionActionEnum;
use common\enums\ValueStateEnum;
use common\enums\WarnEnum;
use common\helpers\BaseHtml;
use common\helpers\Url;
use common\helpers\Html;
use common\models\monitor\project\point\Value;

$this->title = '首页';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<style>
    .info-box-number {
        font-size: 20px;
    }

    .info-box-content {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .table-responsive tr td{
        padding: 0px;
    }
</style>
<div class="row">
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-upload blue"></i> <?= $pointCount ?? 0 ?></span>
                <span class="info-box-text">监测点位</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-flash blue"></i> <?= $deviceCount['online'] ?? 0 ?>/<?= $deviceCount['all'] ?? 0 ?></span>
                <span class="info-box-text">在线设备</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-card blue"></i> <?= $card['deal'] ?? 0 ?>/<?= $card['all'] ?? 0 ?></span>
                <span class="info-box-text">物联卡</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-ios-checkmark-outline blue"></i> <?= $valueCount ?? 0 ?></span>
                <span class="info-box-text">待审核数据</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-ios-bell blue"></i> <?= $warn['deal'] ?? 0 ?></span>
                <span class="info-box-text">报警待处理</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-clipboard blue"></i> <?= $bell['deal'] ?? 0 ?></span>
                <span class="info-box-text">任务提醒</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>

    <div class="col-md-5 col-xs-12">
        <div class="box box-solid">
            <div class="box-header">
                <i class="fa fa-bell blue" style="font-size: 8px"></i>
                <h3 class="box-title">报警数统计</h3>

            </div>
            <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['warn-between-count']),
                    'height' => '230px'
                ]
            ]) ?>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->

        <!-- /.box-body -->
    </div>
    <!-- /.box -->
    <!-- <div class="col-md-4 col-xs-12">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-bullhorn red" style="font-size: 8px"></i>
                <h3 class="box-title">消息提示</h3>
                <div class="box-tools">
                    <?= Html::a('<i class="fa fa-ellipsis-h" style="font-size:8px" aria-hidden="true"></i>', ['/monitor-notify/remind'],) ?>
                </div>
            </div>
            <div class="box-body table-responsive" style="height:310px">
                <div class="col-xs-12">
                    <table class="table table-hover" style="table-layout:fixed">
                        <tr>
                            <th>时间</th>
                            <th>类型</th>
                            <th width="220px">内容</th>
                            <th>操作</th>
                        </tr>
                        <?php foreach ($notify as $key => $value) : ?>
                            <tr>
                                <td><?= date('Y-m-d', $value['created_at']) . ($value['created_at'] > strtotime('-1 day') ? ' <span class="label label-warning" style="font-size:2px">NEW</span>' : '') ?></td>
                                <td><?= SubscriptionActionEnum::$listExplain[$value['action']] ?></td>
                                <td style="overflow: hidden;text-overflow:ellipsis;white-space: nowrap;">
                                    <?= $value['content'] ?>
                                </td>
                                <td>
                                    <?= Html::a('查看', ['/monitor-notify/view', 'id' => $value['id']], $options = [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                        'class' => 'blue'
                                    ]); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div> -->
    <!-- 点位最新数据 -->
    <div class="col-md-7 col-xs-12">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-database red" style="font-size: 8px"></i>
                <h3 class="box-title">最新数据</h3>
                <div class="box-tools">
                    <?= Html::a('<i class="fa fa-ellipsis-h"></i>', ['/monitor-data/value/index','state' => ValueStateEnum::ENABLED],[
                        'class' => 'openContab',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive" style="height:310px">
                <div class="col-xs-12">
                    <table class="table table-hover" style="table-layout:fixed;font-size:8px">
                        <tr>
                            <th>时间</th>
                            <th>房屋</th>
                            <th>点位</th>
                            <th>数据</th>
                            <th>报警</th>
                            <th>状态</th>
                        </tr>
                        <?php foreach ($valueList as $key => $value) : ?>
                            <tr>
                                <td><?= date('m-d H:i', $value['event_time']) . ($value['event_time'] > strtotime('-1 hour') ? ' <span class="label label-warning" style="font-size:2px">NEW</span>' : '') ?></td>
                                <td><?= Html::a($value['house']['title'],['/monitor-project/house/view','id' => $value['house']['id']], $options = [
                                    'class' => 'openContab'
                                ]) ?></td>
                                <td><?= Html::a($value['parent']['title'],['/monitor-project/point/view','id' => $value['pid']], $options = [
                                    'class' => 'openContab'
                                ]) ?></td>
                                <td>
                                    <?= $value['value'] ?> 
                                    <?php if(($num = round(($value['value'] - Value::getPrevValue($value['id'])),4)) > 0):?>
                                        <i class="fa fa-long-arrow-up red"><?= $num ?></i>
                                    <?php else:?>
                                        <i class="fa fa-long-arrow-down blue"><?= $num ?></i>
                                    <?php endif;?>
                                </td>
                                <td>
                                    <?= WarnEnum::getValue($value['warn']) ?>
                                </td>
                                <td>
                                    <?= ValueStateEnum::getValue($value['state']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <div class="box box-solid">
            <div class="box-header">
                <i class="fa fa-area-chart blue" style="font-size: 8px"></i>
                <h3 class="box-title">华为云数据统计</h3>
            </div>
            <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['huawei-between-count']),
                    'height' => '240px'
                ]
            ]) ?>
            <!-- /.box-body -->
        </div>
    </div>
    <div class="col-md-6 col-xs-12">
        <div class="box box-solid">
            <div class="box-header">
                <i class="fa fa-area-chart blue" style="font-size: 8px"></i>
                <h3 class="box-title">阿里云数据统计</h3>
            </div>
            <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['ali-between-count']),
                    'height' => '240px'
                ]
            ]) ?>
        </div>
    </div>
</div>

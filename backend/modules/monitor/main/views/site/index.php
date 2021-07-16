<?php

use common\enums\ValueStateEnum;
use common\enums\WarnEnum;
use common\helpers\Url;
use common\helpers\Html;

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
</style>
<div class="row">
    <div class="col-md-8 col-sm-8 col-xs-12">

        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <div class="info-box-content p-md">
                    <span class="info-box-number"><i class="icon ion-card green"></i> <?= $houseCount['monitor'] ?? 0 ?>/<?= $houseCount['all'] ?? 0 ?></span>
                    <span class="info-box-text">监测房屋</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <div class="info-box-content p-md">
                    <span class="info-box-number"><i class="icon ion-ios-pulse red"></i> <?= $pointCount['monitor'] ?? 0 ?>/<?= $pointCount['all'] ?? 0 ?></span>
                    <span class="info-box-text">监测点</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-4 col-sm-6 col-xs-12">
            <div class="info-box">
                <div class="info-box-content p-md">
                    <span class="info-box-number"><i class="icon ion-ios-lightbulb-outline magenta"></i> <?= $warn['deal'] ?? 0 ?>/<?= $warn['all'] ?? 0 ?></span>
                    <span class="info-box-text">报警处理</span>
                </div>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </div>
        <div class="col-md-12 col-xs-12">
            <div class="box box-solid">
                <div class="box-header">
                    <i class="fa fa-bell blue" style="font-size: 8px"></i>
                    <h3 class="box-title">报警数统计</h3>
                </div>
                <?= \common\widgets\echarts\Echarts::widget([
                    'config' => [
                        'server' => Url::to(['warn-between-count']),
                        'height' => '315px'
                    ]
                ]) ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
    <div class="col-md-4 col-sm-4 col-xs-12">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-bullhorn blue" style="font-size: 8px"></i>
                <h3 class="box-title">报警通知</h3>
            </div>
            <div class="box-body table-responsive" style="height:500px">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <th>时间</th>
                            <th>户主</th>
                            <th>监测点</th>
                            <th>报警等级</th>
                            <th>处理方式</th>
                            <th>操作</th>
                        </tr>
                        <?php foreach ($warnList as $key => $value) : ?>
                            <tr>
                                <td><?= date('Y-m-d H:i:s', $value['created_at']) ?></td>
                                <td><?= Html::a($value['house']['title'], ['/monitor-project/house/view', 'id' =>  $value['house']['id']], $options = ['class' => 'openContab']) ?></td>
                                <td><?= Html::a($value['house']['title'], ['/monitor-project/point/view', 'id' =>  $value['point']['id']], $options = ['class' => 'openContab']) ?></td>
                                <td><?= WarnEnum::getValue($value['warn']) ?></td>
                                <td><?= ValueStateEnum::getValue($value['state']) ?></td>
                                <td></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
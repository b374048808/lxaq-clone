<?php

use common\helpers\Url;

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
    <div class="col-md-12 col-xs-12">
        <div class="box box-solid">
        <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">华为数据统计</h3>
            </div>
            <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['huawei-between-count']),
                    'height' => '315px'
                ]
            ]) ?>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
    <div class="col-md-12 col-xs-12">
        <div class="box box-solid">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">阿里数据统计</h3>
            </div>
            <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['ali-between-count']),
                    'height' => '315px'
                ]
            ]) ?>
            <!-- /.box-body -->
        </div>
        <!-- /.box -->
    </div>
</div>
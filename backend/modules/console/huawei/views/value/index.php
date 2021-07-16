<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

$this->title = '历史数据';
$this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => ['/console-huawei/device/index']];
$this->params['breadcrumbs'][] = ['label' => '设备概况', 'url' => ['/console-huawei/device/view','id' => $pid]];
$this->params['breadcrumbs'][] = ['label' => $this->title];

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit', 'cate_id' => $cate_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>

                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="row">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['index', 'pid' => $pid]),
                        'method' => 'get'
                    ]); ?>
                    <div class="col-sm-6">
                        <div class="input-group drp-container">
                            <?= DateRangePicker::widget([
                                'name' => 'queryDate',
                                'value' => $from_date . '-' . $to_date,
                                'readonly' => 'readonly',
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'startAttribute' => 'from_date',
                                'endAttribute' => 'to_date',
                                'startInputOptions' => ['value' => $from_date ?: date('Y-m-d', strtotime("-6 day"))],
                                'endInputOptions' => ['value' => $to_date ?: date('Y-m-d')],
                                'pluginOptions' => [
                                    'locale' => ['format' => 'Y-m-d'],
                                ]
                            ]) . $addon; ?>
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <?= Html::submitButton('<i class="fa fa-search"></i>搜索', ['class' => 'btn btn-white']) ?>
                        <?= Html::linkButton(['export','pid' => $pid ,'from_date' => $from_date ,'to_date' => $to_date ],'<i class="fa fa-share-square"></i>导出Excel', ['class' => 'btn btn-white']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'id',
                        ],
                        'serviceType',
                        'notifyType',
                        [
                            'header' => '上传时间',
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {status} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看详情', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:31:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 11:44:59
 * @Description: 
 */

use common\enums\VerifyEnum;
use common\helpers\BaseHtml;
use yii\grid\GridView;
use common\helpers\Html;

$this->title = '报告列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::linkButton(['/monitor-project/report-verify/index'],'审核记录') ?>
                    <?= Html::linkButton(['export'], '<i class="fa fa-cloud-upload"></i> 导出表格', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                    <?= Html::linkButton(['recycle'], '<i class="fa fa-trash"></i> 回收站'); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'file_name',
                        [
                            'attribute' => 'verify',
                            'value' => function ($queue) {
                                return VerifyEnum::getValue($queue['verify']);
                            },
                            'filter' => VerifyEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '上传人',
                            'attribute'   => 'user.realname'
                        ],
                        'description',
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ], 
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {audit} {edit} {status} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['view', 'id' => $model->id], '详情', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'audit' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['ajax-audit', 'id' => $model->id], '状态', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                        'class' => 'blue'
                                    ]);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'status'  => function ($url, $model, $key) {
                                    return BaseHtml::status($model['status']);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
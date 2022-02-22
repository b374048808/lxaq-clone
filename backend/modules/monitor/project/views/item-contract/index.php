<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:18:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-01 12:02:38
 * @Description: 
 */

use common\enums\monitor\ItemStepsEnum;
use common\enums\VerifyEnum;
use common\helpers\BaseHtml;
use yii\grid\GridView;
use common\helpers\Html;

$this->title = '项目管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= BaseHtml::a('步骤负责人', ['/monitor-project/steps-member/index'], $options = []) ?>
                    <?= BaseHtml::create(['edit']) ?>
                    <?= BaseHtml::linkButton(['recycle'], '<i class="fa fa-trash"></i> 回收站'); ?>
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
                        [
                            'header' => '上传',
                            'attribute' => 'manager.realname',
                        ],
                        [
                            'header' => '经办人',
                            'attribute' => 'member.realname',
                        ],
                        'item.title',
                        'money',
                        [
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {edit} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['view', 'id' => $model->id], '详情', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return BaseHtml::edit(['ajax-edit', 'id' => $model->id],'编辑', ['data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',]);
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
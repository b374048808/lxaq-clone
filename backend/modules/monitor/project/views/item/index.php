<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:18:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-14 10:19:14
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
                    <?= Html::linkButton(['/monitor-project/steps-member/index'], '<i class="fa fa-users"></i>步骤负责人', $options = []) ?>
                    <?= Html::linkButton(['export'], '导出', $options = [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                    <?= Html::create(['edit'], '创建', [
                        'class' => 'btn btn-white btn-sm'
                    ]) ?>
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
                        [
                            'attribute' => 'title',
                            'value' => function ($queue) {
                                return Html::a($queue['title'], ['view', 'id' => $queue['id']], $options = []);
                            },
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '发布者',
                            'attribute' => 'user.realname',
                        ],
                        [
                            'attribute' => 'audit',
                            'value' => function ($queue) {
                                return VerifyEnum::html($queue['audit']);
                            },
                            'filter' => VerifyEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'steps',
                            'value' => function ($queue) {
                                return ItemStepsEnum::getValue($queue['steps']);
                            },
                            'filter' => ItemStepsEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{audit} {view} {edit} {destroy}',
                            'buttons' => [
                                'audit' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['ajax-audit', 'id' => $model->id], '状态', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['view', 'id' => $model->id], '详情');
                                },
                                'edit' => function ($url, $model, $key) {
                                    return BaseHtml::edit(['edit', 'id' => $model->id]);
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
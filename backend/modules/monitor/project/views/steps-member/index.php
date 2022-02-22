<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-12 14:03:52
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 15:53:13
 * @Description: 
 */

use common\enums\monitor\ItemStepsEnum;
use yii\grid\GridView;;
use common\helpers\Html;

$this->title = '步骤负责人';
$this->params['breadcrumbs'][] = ['label' => '项目列表','url' => ['/monitor-project/item/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
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
                            'attribute' => 'step_id',
                            'value' => function($queue){
                                return ItemStepsEnum::getValue($queue['step_id']);
                            },
                            'filter'    => ItemStepsEnum::getMap(),
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'member.realname',
                            'format' => 'raw',
                        ],
                        'description',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ]
                ]); ?>

            </div>
        </div>
    </div>
</div>
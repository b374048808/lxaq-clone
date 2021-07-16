<?php

use common\enums\CardEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\Url;

$this->title = '分组列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];

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
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        return ['class' => $index % 2 == 0 ? $key : 'label-green'];
                    },
                    'columns' => [
                        [
                            'attribute' => 'iccid',
                            'value' => function ($model, $key, $index, $column) {
                                return Html::a($model['iccid'], ['view','id' => $model['id']], $options = []);
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'type',
                            'value' => function ($model, $key, $index, $column) {
                               
                                return CardEnum::getValue($model['type']);
                            },
                            'filter' => CardEnum::getMap(),
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'package',
                            'value' => function ($model, $key, $index, $column) {
                               
                                return CardEnum::getPackageValue($model['package']);
                            },
                            'filter' => CardEnum::getPackageMap(),
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'operator',
                            'value' => function ($model, $key, $index, $column) {
                               
                                return CardEnum::getOperatorValue($model['operator']);
                            },
                            'filter' => CardEnum::getOperatorMap(),
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'active_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        [
                            'attribute' => 'expiration_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
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
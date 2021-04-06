<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\PointEnum;

$this->title = '监测点位管理';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <?php if (isset($pid)) : ?>
                    <div class="box-tools">
                        <?= Html::create(['ajax-edit', 'pid' => $pid], '创建', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModalLg',
                        ]) ?>
                    </div>
                <?php endif;  ?>
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
                        'title',
                        [
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return PointEnum::getMap()[$que->type];
                            },
                            'filter' => PointEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {edit} {destroy}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看');
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
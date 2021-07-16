<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 09:19:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 17:58:02
 * @Description: 
 */

use common\enums\JudgeEnum;
use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\enums\PointEnum;
use common\enums\WarnEnum;

$this->title = '触发器';
$this->params['breadcrumbs'][] = ['label' => '规则', 'url' => ['/monitor-rule/simple/index']];
$this->params['breadcrumbs'][] = ['label' => '关联常见', 'url' => ['/monitor-rule/child/index','rule_id' => $pid]];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit','pid' => $pid], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
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
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return PointEnum::getValue($que->type);
                            },
                            'filter' => PointEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'warn',
                            'value' => function ($que) {
                                return WarnEnum::getValue($que->warn);
                            },
                            'filter' => WarnEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'judge',
                            'value' => function ($que) {
                                return JudgeEnum::getValue($que->judge);
                            },
                            'filter' => JudgeEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        'value',
                        [
                            'attribute' => 'sort',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return Html::sort($model->sort);
                            },
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {destroy}',
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
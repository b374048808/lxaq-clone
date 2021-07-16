<?php

use yii\grid\GridView;
use common\helpers\Html;
use yii\helpers\Url;

$this->title = '场景关联';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['/monitor-rule/simple/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => '#'];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">关联建筑物</h3>
                <div class="box-tools">
                    <?= Html::linkButton(['ajax-edit','rule_id'=> $rule_id], '添加建筑物', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                     <?= Html::linkButton(['/monitor-rule/item/index','pid'=> $rule_id], '触发器') ?>
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
                        'house.title',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {destroy}',
                            'buttons' => [
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
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
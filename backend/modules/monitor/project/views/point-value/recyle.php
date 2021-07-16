<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 11:49:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-24 14:52:16
 * @Description: 
 */

use common\helpers\Html;
use common\enums\PointEnum;
use common\enums\ValueTypeEnum;
use yii\grid\GridView;
use common\enums\WarnEnum;
use common\enums\ValueStateEnum;


$this->title = '回收站';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['/monitor-project/house/index']];
$this->params['breadcrumbs'][] = ['label' => $pointModel->house->title, 'url' => ['/monitor-project/house/view', 'id' => $pointModel->house->id], [
    'data-toggle' => 'modal',
    'data-target' => '#ajaxModal',
]];
$this->params['breadcrumbs'][] = ['label' => $pointModel->title, 'url' => ['/monitor-project/point/view', 'id' => $pointModel->id]];
$this->params['breadcrumbs'][] = ['label' => '设备数据', 'url' => ['/monitor-project/point-value/index', 'pid' => $pointModel->id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                   
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => true, // 不显示#
                        ],
                        [
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        'value',
                        [
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return ValueTypeEnum::getValue($que->type);
                            },
                            'filter' => ValueTypeEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "报警等级",
                            'attribute' => 'warn',
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[$model->warn];
                            },
                            'filter' => WarnEnum::getMap(),
                            'format' => 'raw',
                        ],
                        [
                            'label' => '状态',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return ValueStateEnum::getValue($model->state);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{show} {delete}',
                            'buttons' => [
                                'show' => function ($url, $model, $key) {
                                    return Html::linkButton(['show', 'id' => $model->id],'还原');
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

                <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>

            </div>
        </div>
    </div>
</div>
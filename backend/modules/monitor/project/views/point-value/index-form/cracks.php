<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 14:47:54
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-06 14:45:55
 * @Description: 
 */

use common\enums\PointEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\enums\ValueStateEnum;
?>
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
            'template' => '{view} {edit} {status} {destroy}',
            'buttons' => [
                'view' => function ($url, $model, $key) {
                    return Html::linkButton(['view', 'id' => $model->id,'type' => PointEnum::CRACKS], '查看', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]);
                },
                'edit' => function ($url, $model, $key) {
                    return Html::edit(['ajax-edit', 'id' => $model->id,'type' => PointEnum::CRACKS], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]);
                },
                'status' => function ($url, $model, $key) {
                    return Html::status($model->status);
                },
                'destroy' => function ($url, $model, $key) {
                    return Html::delete(['destroy', 'id' => $model->id,'type' => PointEnum::CRACKS]);
                },
            ],
        ],
    ],
]); ?>

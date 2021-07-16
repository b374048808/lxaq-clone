<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 14:19:49
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 14:45:54
 * @Description: 
 */

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;

$this->title = '历史数据';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
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
                            'visible' => true, // 不显示#
                        ],
                        'user.username',
                        [
                            'header' => "监测点位",
                            'attribute' => 'point.title',
                            'value' => function ($model) {
                                return Html::a($model['point']['title'], ['/monitor-project/point/view','id' => $model['point']['id']], $options = ['class' => 'openContab']);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',

                        ],
                        'value',
                        [
                            'attribute' => 'type',
                            'value' => function ($model) {
                                return ValueTypeEnum::getValue($model['type']);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',

                        ],
                        [
                            'attribute' => 'warn',
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[$model['warn']];
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',

                        ],
                        [
                            'attribute' => 'state',
                            'value' => function ($model) {
                                return ValueStateEnum::getValue($model['state']);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',

                        ],
                        [
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::a('查看', ['view', 'id' => $model['id']], [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
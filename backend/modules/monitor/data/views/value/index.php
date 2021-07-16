<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 14:49:39
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 18:00:02
 * @Description: 
 */

use common\enums\PointEnum;
use common\enums\ValueStateEnum;
use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;

$stateText = '';
switch ($state) {
    case 2:
        $stateText = '待审核';
        break;
    case 1:
        $stateText = '审核通过';
        break;
    case 0:
        $stateText = '驳回';
        break;

    default:
        # code...
        break;
}
$this->title = $stateText;
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
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
                        [
                            'header' => '房屋',
                            'attribute' => 'house.title',
                            'value' => function ($que) {
                                return Html::a($que['house']['title'], ['/monitor-project/house/view', 'id' => $que['house']['id']], $options = ['class' => 'openContab']);
                            },
                            'filter' => true, //显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '监测点',
                            'attribute' => 'parent.title',
                            'value' => function ($que) {
                                return Html::a($que['parent']['title'], ['/monitor-project/point/view', 'id' => $que['pid']], $options = ['class' => 'openContab']);
                            },
                            'format' => 'html',
                        ],
                        [
                            'header' => '监测点类型',
                            'attribute' => 'parent.type',
                            'value' => function ($que) {
                                return PointEnum::getValue($que['parent']['type']);
                            },
                            'filter' => PointEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'value',
                            'filter' => false, //显示搜索框
                            'format' => 'html',
                        ],
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
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {state} {edit} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id, 'type' => PointEnum::ANGLE], '查看', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'state' => function ($url, $model, $key) {
                                    return Html::linkButton(
                                        ['ajax-state', 'id' => $model->id, 'type' => PointEnum::ANGLE],
                                        $model['state'] == ValueStateEnum::AUDIT ? '审核' : '状态',
                                        [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                            'class' => ($model['state'] == ValueStateEnum::AUDIT ? 'text-primary ' : 'text-white'),
                                        ]
                                    );
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
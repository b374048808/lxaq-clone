<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-29 09:20:22
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 17:55:49
 * @Description: 
 */

use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\enums\PointEnum;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;

$this->title = '报警管理';
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
                        ],
                        [
                            'header' => '房屋',
                            'value' => function ($que) {
                                return Html::a($que['house']['title'], ['/monitor-project/house/view','id' => $que['house']['id']], $options = ['class' => 'openContab']);
                            },
                            'format' => 'html',
                        ],
                        [
                            'header' => '监测点位',
                            'value' => function ($que) {
                                return Html::a($que['point']['title'], ['/monitor-project/point/view','id' => $que['point']['id']], $options = ['class' => 'openContab']);
                            },
                            'filter' => PointEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'warn',
                            'value' => function ($que) {
                                return WarnEnum::$spanlistExplain[$que['warn']];
                            },
                            'filter' => WarnEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'state',
                            'value' => function ($que) {
                                return WarnStateEnum::getValue($que['state']);
                            },
                            'filter' => WarnStateEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
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
                            'template' => '{view} {edit} {status} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['/monitor-project/warn/view','id' => $model['id']],'查看');
                                },
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
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 09:00:40
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-07 14:39:11
 * @Description: 
 */

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\WarnEnum;

$this->title = '监测点位报警日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
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
                            'header' => "房屋",
                            'attribute' => 'house.title',
                            'filter' => true, //不显示搜索框
                            'format' => 'raw',
                        ],
                        [
                            'header' => "监测点位",
                            'attribute' => 'point.title',
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'value',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'attribute' => 'item.warn',
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[$model->item->warn];
                            },
                            'filter' => true, //不显示搜索框
                            'filter' => WarnEnum::getMap(),
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看详情', [
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

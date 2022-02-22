<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 10:28:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 14:53:27
 * @Description: 
 */

use common\helpers\BaseHtml as Html;
use yii\grid\GridView;

$this->title = '模板管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '添加模板', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="active tab-pane">
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
                                'attribute' => 'title',
                                'value' => function ($model) {
                                    return Html::a($model['title'], ['view', 'id' => $model['id']], $options = []);
                                },
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'push_id',
                                'value' => function ($queue) {
                                    return $queue['worker']['username'];
                                },
                            ],
                            'description',
                            // [
                            //     'attribute' => 'sort',
                            //     'value' => function ($model) {
                            //         return Html::sort($model->sort);
                            //     },
                            //     'filter' => false,
                            //     'format' => 'raw',
                            //     'headerOptions' => ['class' => 'col-md-1'],
                            // ],
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
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
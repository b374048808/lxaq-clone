<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-22 15:49:19
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-24 10:17:45
 * @Description: 
 */

use common\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = '命令';
$this->params['breadcrumbs'][] = ['label' => '产品管理', 'url' => Url::to(['/console-ali/product/index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['/console-ali/product/view', 'id' => $pid]) ?>">概况</a></li>
                <li class="active"><a href="">命令</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit', 'pid' => $pid], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
            <div class="box-body">
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
                            'title',
                            'content',
                            [
                                'attribute' => 'sort',
                                'value' => function ($model) {
                                    return Html::sort($model->sort);
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{status} {delete}',
                                'buttons' => [
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
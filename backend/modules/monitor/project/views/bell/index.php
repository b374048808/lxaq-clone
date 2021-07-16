<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-02 09:56:53
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 09:35:50
 * @Description: 
 */

use common\enums\monitor\BellEnum;
use common\enums\monitor\BellStateEnum;
use common\helpers\BaseHtml;
use yii\grid\GridView;
use common\helpers\Html;

$this->title = '提醒列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">提醒列表</h3>
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
                            'attribute' => 'event_time',
                            'format' => ['date', 'php:Y-m-d'], //不显示搜索框
                            'filter' => false
                        ],   
                        [
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return BellEnum::getValue($que['type']);
                            },
                            'filter' => BellEnum::getMap(),
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'house.title',
                            'value'=> function($que){
                                return Html::a($que['house']['title'], ['/monitor-project/house/view','id' => $que['pid']], $options = ['class' => 'openContab']);

                            },
                            'filter' => true,
                            'format' => 'html'
                        ],                               
                        [
                            'attribute' => 'state',
                            'value' => function ($que) {
                                return BellStateEnum::getValue($que['state']);
                            },
                            'filter' => BellStateEnum::getMap(),
                            'format' => 'html'
                            
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {destroy}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return BaseHtml::edit(['/monitor-project/bell/ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'status' => function($url, $model, $key) {
                                    return BaseHtml::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['/monitor-project/bell/destroy', 'id' => $model->id], '删除');
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
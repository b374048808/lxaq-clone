<?php

use common\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\console\iot\ali\Directive;

$this->title = '命令日志';
if ($id) {
    $this->params['breadcrumbs'][] = ['label' => '设备', 'url' => Url::to(['/console-ali/device/index'], $schema = true)];
    $this->params['breadcrumbs'][] = ['label' => '详情', 'url' => Url::to(['/console-ali/device/directive','id' => $id], $schema = true)];
}else{
    $this->params['breadcrumbs'][] = ['label' => '设备日志', 'url' => Url::to(['/console-ali/directive-log/index'], $schema = true)];
}

$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
               
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
                            'device.number', 
                            [
                                'header' => "预警",
                                'attribute' => 'directive.id',
                                'value' => function($que){
                                    return $que->directive->title;
                                },
                                'filter' => Directive::getMap(),
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'created_at',
                                'filter' => false, //不显示搜索框
                                'format' => ['date', 'php:Y-m-d H:i:s'],
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {status} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::linkButton(['view', 'id' => $model->id], '查看', [
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
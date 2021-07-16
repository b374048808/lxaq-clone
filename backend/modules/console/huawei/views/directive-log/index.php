<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-21 15:59:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-24 10:03:11
 * @Description: 
 */

use common\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
use common\models\console\iot\huawei\Directive;

$this->title = '命令日志';
if ($id) {
    $this->params['breadcrumbs'][] = ['label' => '设备列表', 'url' => Url::to(['/console-huawei/device/index'], $schema = true)];
    $this->params['breadcrumbs'][] = ['label' => '命令列表', 'url' => Url::to(['/console-huawei/device/directive','id' => $id], $schema = true)];
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
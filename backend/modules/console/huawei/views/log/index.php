<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-13 10:25:03
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 08:52:43
 * @Description: 
 */

use common\helpers\BaseHtml as Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = '日志管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
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
                                'header' => '所属产品',
                                'attribute' => 'product_name',
                                'value' => 'product.name',
                                'filter' => true, //不显示搜索框
                                'format' => 'html',
                            ],
                            [
                                'header' => '所属设备',
                                'attribute' => 'device_number',
                                'value' => 'device.number',
                                'filter' => true, //不显示搜索框
                                'format' => 'html',
                            ],
                            'notifyType',
                            [
                                'attribute' => 'event_time',
                                'filter' => false, //不显示搜索框
                                'format' => ['date', 'php:Y-m-d H:i:s'],
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template'=> '{view} {status} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::linkButton(['view', 'id' => $model->id], '查看详情', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]);
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
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-30 10:38:45
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 11:00:52
 * @Description: 
 */

use common\enums\mini\MessageReasonEnum;
use yii\grid\GridView;
use common\helpers\Html;

$this->title = '小程序消息管理';
$this->params['breadcrumbs'][] = $this->title;

/* @var $this \yii\web\View */
/* @var $dataProvider \yii\data\ActiveDataProvider */
/* @var $searchModel \common\models\base\SearchModel */

?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        [
                            'header' => '触发用户',
                            'attribute' => 'member.realname',
                            'filter' => false, //不显示搜索框
                        ],
                        'error_code',
                        'error_msg',
                        'error_data',
                        'ip',
                        [
                            'attribute' => 'use_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看详情', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>
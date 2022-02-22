<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 10:28:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 16:04:28
 * @Description: 
 */

use common\helpers\BaseHtml as Html;
use common\models\company\service\StepsOn;
use yii\grid\GridView;

$this->title = '模板管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-xs-2">
        <?= $this->render('_left_menu') ?>
    </div>
    <div class="col-xs-10">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit'], '立项'); ?>
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
                                'attribute' => 'is_system',
                                'value' => function($model){
                                    return $model['is_system']?'是':'否';
                                },
                                'filter' => [
                                    '0'   => '否',
                                    '1'   => '是',
                                ],
                                'format' => 'html',
                            ],
                            [
                                'header' => '进度',
                                'value' => function($model){
                                    $bar = StepsOn::getBar($model['id']);
                                    return '<div class="progress">
                                    <div class="progress-bar progress-bar-striped" role="progressbar" aria-valuenow="'.$bar.'" aria-valuemin="0" aria-valuemax="100" style="width: '.$bar.'%;">
                                    '.$bar.'%
                                    </div>
                                  </div>';
                                },
                                'format' => 'html'
                            ],
                            [
                                'attribute' => 'manager_id',
                                'value' => function ($queue) {
                                    return $queue['worker']['username'];
                                },
                            ],
                            // 'description',
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
                                        return Html::edit(['edit', 'id' => $model->id], '编辑');
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
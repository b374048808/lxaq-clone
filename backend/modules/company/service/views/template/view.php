<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 10:28:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-25 10:39:43
 * @Description: 
 */

use common\helpers\BaseHtml as Html;
use yii\grid\GridView;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '模板列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['/company-service/steps/ajax-edit', 'pid' => $model['id']], '添加步骤', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        //重新定义分页样式
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'visible' => true, // 不显示#
                            ],
                            [
                                'attribute' => 'title',
                            ],
                            [
                                'attribute' => 'push_id',
                                'value' => function ($model) {
                                    return $model['worker']['username'];
                                },
                            ],
                            'description',
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit} {status} {delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['/company-service/steps/ajax-edit', 'id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::delete(['/company-service/steps/delete', 'id' => $model->id]);
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
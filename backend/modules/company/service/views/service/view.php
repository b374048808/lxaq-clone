<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 10:28:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 17:39:59
 * @Description: 
 */

use common\enums\company\StepStatusEnum;
use common\helpers\BaseHtml as Html;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\grid\GridView;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">信息</h3>
                <div class="box-tools">
                    <?= Html::edit(['edit', 'id' => $model['id']], '<i class="fa fa-edit"></i>编辑'); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>项目名称</td>
                        <td><?= $model['title'] ?></td>
                        <td>截止时间</td>
                    </tr>
                </table>
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
                                'header' => '步骤',
                                'value' => function ($model) {
                                    return $model['step']['title'];
                                }
                            ],
                            [
                                'attribute' => 'push_id',
                                'value' => function ($model) {
                                    return $model['worker']['username'];
                                },
                            ],
                            'description',
                            [
                                'attribute' => 'status',
                                'value' => function ($model) {
                                    return StepStatusEnum::getValue($model['status']);
                                },
                                'format' => 'html'
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{feedback} {edit} {status} {delete}',
                                'buttons' => [
                                    'feedback'  => function ($url, $model, $key) {
                                        return Html::edit(['/company-service/steps-on/feedback', 'id' => $model->id], '反馈', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]);
                                    },
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['/company-service/steps-on/ajax-edit', 'id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]);
                                    },
                                    'delete' => function ($url, $model, $key) {
                                        return Html::delete(['/company-service/steps-on/delete', 'id' => $model->id]);
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
<?php
use common\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;
$this->title = '产品管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>
<div class="row">
    <div class="col-sm-12">
    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['iot/huawei-Product/index']) ?>"> 菜单分类</a></li>
                <li><a href="<?= Url::to(['iot/ali-Product/index']) ?>"> 菜单分类</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit', 'cate_id' => $cate_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
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
                            'name',
                            'product_key',
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
                                'template'=> '{view} {status} {delete}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::linkButton(['view', 'id' => $model->id], '查看');
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
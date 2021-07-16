<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\models\console\iot\ali\Product;
use common\models\monitor\project\point\AliMap;
use common\helpers\BaseHtml;

$this->title = '设备列表';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<script>
    function func() {
        var form = document.getElementById("ruleForm"); //获取form表单对象
        form.submit(); //form表单提交
    };
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= BaseHtml::create(['ajax-edit', 'cate_id' => $cate_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                    <?= BaseHtml::linkButton(['recycle'], '<i class="fa fa-trash"></i> 回收站'); ?>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-xs-8">
                    <div class="col-xs-4">
                        <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">设备总数</p>
                        <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $dataProvider->totalCount ?></p>
                    </div>
                    <div class="col-xs-4">
                        <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">在线设备</p>
                        <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $onLine ?></p>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        'device_name',
                        'number',
                        [
                            'header' => '所属产品',
                            'attribute' => 'pid',
                            'value' => 'product.name',
                            'filter' => Product::getMapList(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '关联监测点数量',
                            'value' => function ($model) {
                                return AliMap::getPointCount($model['id']);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '状态',
                            'value' => function ($model) {
                                return $model->deviceStatus;
                            },
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '状态/启用状态',
                            'value' => function ($model) {
                                return $model->status ? '开启' : '禁止';
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '最后上线时间',
                            'attribute' => 'newValue_created_at',
                            'value' => 'newValue.created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::edit(['destroy', 'id' => $model->id]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['destroy', 'id' => $model->id]);
                                },
                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
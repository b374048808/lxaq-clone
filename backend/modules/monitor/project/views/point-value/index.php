<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 11:49:23
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 09:59:05
 * @Description: 
 */

use common\helpers\BaseHtml as Html;
use common\enums\PointEnum;
use common\enums\ValueTypeEnum;
use yii\grid\GridView;
use common\enums\WarnEnum;
use common\enums\ValueStateEnum;
use yii\helpers\Url;



$this->title = (Yii::$app->request->get('valueType') == ValueTypeEnum::MANUAL) ? '人工数据' : '设备数据';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['/monitor-project/house/index']];
$this->params['breadcrumbs'][] = ['label' => $pointModel->house->title, 'url' => ['/monitor-project/house/view', 'id' => $pointModel->house->id], [
    'data-toggle' => 'modal',
    'data-target' => '#ajaxModal',
]];
$this->params['breadcrumbs'][] = ['label' => $pointModel->title, 'url' => ['/monitor-project/point/view', 'id' => $pointModel->id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit', 'pid' => $pointModel['id']], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                    <?= Html::linkButton(['value-rand', 'id' => $pointModel['id']], '<i class="fa fa-random"></i> 生成数据',[
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                    <?= Html::linkButton(['recyle', 'pid' => $pointModel['id'], 'type' => $pointModel['type']], '<i class="fa fa-trash"></i> 回收站'); ?>
                    <?= Html::linkButton(['excel-file', 'pid' => $pointModel['id']], '<i class="fa fa-cloud-download"></i> 批量上传', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                    <?= Html::linkButton(['download', 'pid' => $pointModel['id'], 'type' => $pointModel['type']], '<i class="fa fa-cloud-download"></i> 下载模板'); ?>
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
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => true, // 不显示#
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'values',
                            'headerOptions' => ['width' => '80px']
                        ],
                        [
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        'value',
                        [
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return ValueTypeEnum::getValue($que->type);
                            },
                            'filter' => ValueTypeEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "报警等级",
                            'attribute' => 'warn',
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[$model->warn];
                            },
                            'filter' => WarnEnum::getMap(),
                            'format' => 'raw',
                        ],
                        [
                            'label' => '状态',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return ValueStateEnum::getValue($model->state);
                            },
                            'format' => 'raw',
                        ],

                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {state} {edit} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'state' => function ($url, $model, $key) {
                                    return Html::linkButton(
                                        ['ajax-state', 'id' => $model->id, 'type' => PointEnum::ANGLE],
                                        $model['state'] == ValueStateEnum::AUDIT ? '审核' : '状态',
                                        [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                            'class' => '' . ($model['state'] == ValueStateEnum::AUDIT ? 'text-primary ' : 'text-white'),
                                        ]
                                    );
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <?= Html::a('删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>

            </div>
        </div>
    </div>
</div>
<?php
$url = Url::to(["/monitor-project/point-value/destroy-all", 'id' => $pointModel->id]);
$js = <<<JS
    $(".checkDelete").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
        $.ajax({
            url:"$url",
            type:"post",
            data:{data:keys},
            dataType:"json",
            success:function(e){
                e?location.reload():alert('更新失败!');
            }
        })
    });
JS;
$this->registerJs($js);

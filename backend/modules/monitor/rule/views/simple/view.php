<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;
use common\enums\JudgeEnum;
use common\enums\PointEnum;
use common\enums\WarnEnum;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '报警规则', 'url' => Url::to(['index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-xs-4">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-rule/item/ajax-edit','pid' => $model['id']], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $itemProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return PointEnum::getValue($que->type);
                            },
                            'filter' => PointEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '判断',
                            'value' => function ($que) {
                                return JudgeEnum::getValue($que->judge).$que->value;
                            },
                            'filter' => PointEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'warn',
                            'value' => function ($que) {
                                return WarnEnum::getValue($que->warn);
                            },
                            'filter' => WarnEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {destroy}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['/monitor-rule/item/ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['/monitor-rule/item/destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
    <div class="col-xs-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">关联房屋</h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-rule/child/ajax-edit', 'rule_id' => $model['id']], '关联', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['width' => '80px']
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'points',
                            'headerOptions' => ['width' => '80px']
                        ],
                        [
                            'header' => '建筑物',
                            'value' => 'house.title',
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}',

                        ],
                    ],
                ]); ?>

                <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>

            </div>
        </div>

    </div>
</div>




<?php
$url = Url::to(["/monitor-rule/child/delete-all", 'rule_id' => $model['id']]);
$js = <<<JS
    $(".checkDelete").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
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

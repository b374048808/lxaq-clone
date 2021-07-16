<?php

use yii\grid\GridView;
use common\helpers\Html;
use yii\helpers\Url;

$this->title = '用户房屋授权';
$this->params['breadcrumbs'][] = ['label' => '会员信息', 'url' => ['member/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">关联房屋</h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit', 'member_id' => $id], '关联', [
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
                            'headerOptions' => ['width' => '40px'],
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'checkboxOptions' => function ($model, $key, $index, $column) {
                                return [
                                    'value' => $model->house_id,
                                ];
                            },
                            'headerOptions' => ['width' => '40px'],

                        ],
                        [
                            'header' => '房屋户主或单位信息',
                            'value' => 'house.title',
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete([
                                        'delete', 'member_id' => $model['member_id'], 'house_id' => $model['house_id']
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>
            </div>
        </div>
    </div>
</div>
<?php
$url = Url::to(["/member/house-map/delete-all", 'member_id' => $id]);
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

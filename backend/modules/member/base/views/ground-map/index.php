<?php

use yii\grid\GridView;
use common\helpers\Html;
use yii\helpers\Url;

$this->title = '分组内容';
$this->params['breadcrumbs'][] = ['label' => '分组列表', 'url' => ['/monitor-project/ground/index']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">概况</a></li>
                <li><a href="<?= Url::to(['/member-base/house-map/index', 'id' => $model['id']], $schema = true) ?>">关联建筑</a></li>
                <li class="pull-right">
                <?= Html::edit(['/member-base/ground/ajax-edit', 'id' => $model['id']], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                </li>
            </ul>
            <div class="box-body table-responsive">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <td>名称</td>
                            <td><?= $model['title'] ?></td>
                            <td>级别</td>
                            <td><?= $model['level'] ?></td>
                            <td>上级</td>
                            <td><?= $model['parent']?$model['parent']['title']:'顶级菜单' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">关联账号</h3>
                <div class="box-tools">
                    <?= Html::create(['/member-base/ground-map/ajax-edit', 'ground_id' => $model['id']], '关联', [
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
                                    'value' => $model->member_id,
                                ];
                            },
                            'headerOptions' => ['width' => '40px'],
                            
                        ],
                        [
                            'header' => '用户账号',
                            'value' => 'member.username',
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
                                        'delete', 'ground_id' => $model['ground_id'], 'member_id' => $model['member_id']
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
$url = Url::to(["/member-base/ground-map/delete-all", 'ground_id' => $model['id']]);
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

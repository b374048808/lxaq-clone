<?php

use yii\grid\GridView;
use common\helpers\Html;
use yii\helpers\Url;

$this->title = '分组内容';
$this->params['breadcrumbs'][] = ['label' => '分组列表', 'url' => ['/monitor-project/ground/index']];
$this->params['breadcrumbs'][] = ['label' => $groundModel->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">概况</h3>
                <div class="box-tools">
                    <?= Html::edit(['/monitor-project/ground/ajax-edit', 'id' => $ground_id], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <td>名称</td>
                            <td><?= $groundModel['title'] ?></td>
                            <td>级别</td>
                            <td><?= $groundModel['level'] ?></td>
                            <td>上级</td>
                            <td><?= $groundModel['parent'] ? $groundModel['parent']['title'] : '顶级菜单' ?></td>
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
                <h3 class="box-title">关联点位</h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-project/ground-map/ajax-edit', 'ground_id' => $ground_id], '关联', [
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
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        return ['class' => $index % 2 == 0 ? $key : 'label-green'];
                    },
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'headerOptions' => ['width' => '80px'],
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'points',
                            'headerOptions' => ['width' => '40px'],
                        ],
                        [
                            'header' => '建筑物',
                            'attribute' => 'house.title',
                            'filter' => true, //不显示搜索框
                            'value' => function ($model) {
                                return Html::a($model['house']['title'], ['/monitor-project/house/view','id' => $model['house']['id']], $options = ['class'=>'openContab']);
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'house.address',
                            'format' => 'html'
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
$url = Url::to(["/monitor-project/ground-map/delete-all", 'ground_id' => $ground_id]);

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

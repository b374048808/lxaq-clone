<?php

use common\enums\StatusEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\BaseHtml;
use common\helpers\Url;
use common\models\monitor\project\Point;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '场景联动', 'url' => Url::to(['/monitor-create/simple/index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $model['title']; ?></h3>
                <div class="box-tools">
                    <?= Html::edit(['ajax-edit', 'id' => $model['id']], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                </div>
            </div>
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <td>起始时间</td>
                        <td><?= date('Y-m-d H:i:s', $model['start_time']) ?></td>
                        <td>起始时间</td>
                        <td><?= date('Y-m-d H:i:s', $model['end_time']) ?></td>
                        <td>状态</td>
                        <td><?= StatusEnum::getValue($model['status']) ?></td>
                    </tr>
                    <tr>
                        <td>最小值</td>
                        <td><?= $model['start_value'] ?></td>
                        <td>最值</td>
                        <td><?= $model['end_value'] ?></td>
                        <td>起始时间</td>
                        <td><?= $model['interval'] ?>小时</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">关联点位</h3>
                <div class="box-tools">
                    <?= BaseHtml::create(['/monitor-create/child/ajax-edit', 'simple_id' => $model['id']], '关联', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
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
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'points',
                        ],
                        [
                            'header' => '建筑物',
                            'value' => 'house.title',
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '监测点位',
                            'value' => 'point.title',
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '最新数据时间',
                            'value' => function ($queue) {
                                $model = Point::findOne($queue->point_id);
                                return $model['newValue']['value'] . '  (' . date('m-d H:i', $model['newValue']['event_time']) . ')';
                            },
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{rend} {status} {delete}',
                            'buttons' => [
                                'rend' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['rend', 'id' => $model['id']], '生成数据');
                                },
                                'delete' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['/monitor-create/child/delete', 'id' => $model->id]);
                                },
                            ],

                        ],
                    ],
                ]); ?>

                <?= Html::a('删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>
                <?= Html::a('生成数据', "javascript:void(0);", ['class' => 'btn btn-success checkRand']) ?>

            </div>
        </div>
    </div>
</div>
<?php
$url = Url::to(["/monitor-create/child/delete-all",'simple_id' => $model['id']]);
$randUrl = Url::to(["/monitor-create/child/rand-all",'simple_id' => $model['id']]);
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
    $(".checkRand").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        $.ajax({
            url:"$randUrl",
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

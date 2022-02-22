<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 14:49:39
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-21 18:36:40
 * @Description: 
 */

use common\enums\PointEnum;
use common\enums\ValueStateEnum;
use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\point\Value;
use yii\helpers\Url;

$stateText = '';
switch ($state) {
    case 2:
        $stateText = '待审核';
        break;
    case 1:
        $stateText = '审核通过';
        break;
    case 0:
        $stateText = '驳回';
        break;

    default:
        # code...
        break;
}
$this->title = $stateText;
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?= $this->title; ?></h3>
                    <div class="box-tools">
                        <?= Html::linkButton(['state-all'], '全部改为未报警审核通过') ?>
                    </div>
                </div>
                <div class="box-body table-responsive">

                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                        //重新定义分页样式
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                                'visible' => true, // 不显示#
                            ],
                            [
                                'class' => 'yii\grid\CheckboxColumn',
                                'name' => 'points',
                            ],
                            [
                                'header' => '房屋',
                                'attribute' => 'house.title',
                                'value' => function ($que) {
                                    return Html::a($que['house']['title'], ['/monitor-project/house/view', 'id' => $que['house']['id']], $options = ['class' => 'openContab']);
                                },
                                'filter' => true, //显示搜索框
                                'format' => 'html',
                            ],
                            [
                                'header' => '监测点',
                                'attribute' => 'parent.title',
                                'value' => function ($que) {
                                    return Html::a($que['parent']['title'], ['/monitor-project/point/view', 'id' => $que['pid']], $options = ['class' => 'openContab']);
                                },
                                'format' => 'html',
                            ],
                            [
                                'header' => '监测点类型',
                                'attribute' => 'parent.type',
                                'value' => function ($que) {
                                    return PointEnum::getValue($que['parent']['type']);
                                },
                                'filter' => PointEnum::getMap(), //不显示搜索框
                                'format' => 'html',
                            ],
                            [
                                'attribute' => 'value',
                                'value' => function ($model) {
                                    $num = round($model['value'] - Value::getPrevValue($model['id']), 4);
                                    return $num  > 0
                                        ? $model['value'] . '<i class="fa fa-long-arrow-up red">' . $num . '</i>'
                                        : $model['value'] . '<i class="fa fa-long-arrow-down blue">' . $num . '</i>';
                                },
                                'filter' => false, //显示搜索框
                                'format' => 'html',
                            ],
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
                                'attribute' => 'event_time',
                                'filter' => false, //不显示搜索框
                                'format' => ['date', 'php:Y-m-d H:i:s'],
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{view} {state} {edit} {destroy}',
                                'buttons' => [
                                    'view' => function ($url, $model, $key) {
                                        return Html::linkButton(['view', 'id' => $model->id, 'type' => PointEnum::ANGLE], '查看', [
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
                                                'class' => ($model['state'] == ValueStateEnum::AUDIT ? 'text-primary ' : 'text-white'),
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
                    <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>
                    <?= Html::a('批量修改安全', "javascript:void(0);", ['class' => 'btn btn-success checkWarn']) ?>
                    <?= Html::a('批量审核', "javascript:void(0);", ['class' => 'btn btn-success checkVerify']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php
$deleteUrl = Url::to(["delete-all"]);
$updateUrl = Url::to(["update-all"]);
$js = <<<JS
    $(".checkDelete").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
        $.ajax({
            url:"$deleteUrl",
            type:"post",
            data:{data:keys},
            dataType:"json",
            success:function(e){
                e?location.reload():alert('更新失败!');
            }
        })
    });
    $(".checkWarn").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
        $.ajax({
            url:"$updateUrl",
            type:"post",
            data:{data:keys,warn:1},
            dataType:"json",
            success:function(e){
                console.log(e);
                e?location.reload():alert('更新失败!');
            }
        })
    });
    $(".checkVerify").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        console.log(keys);
        $.ajax({
            url:"$updateUrl",
            type:"post",
            data:{data:keys,state:1},
            dataType:"json",
            success:function(e){
                e?location.reload():alert('更新失败!');
            }
        })
    });
JS;
$this->registerJs($js);

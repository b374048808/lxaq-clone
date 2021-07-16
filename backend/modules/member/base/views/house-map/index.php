<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-29 13:27:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-24 15:47:55
 * @Description: 
 */

use yii\grid\GridView;
use common\helpers\Html;
use yii\helpers\Url;

$this->title = '分组内容';
$this->params['breadcrumbs'][] = ['label' => '分组列表', 'url' => ['/member-base/ground/index']];
$this->params['breadcrumbs'][] = ['label' => $model->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['/member-base/ground-map/index', 'id' => $model['id']], $schema = true) ?>">概况</a></li>
                <li class="active"><a href="#">关联建筑</a></li>
                <li class="pull-right">
                <?= Html::create(['/member-base/house-map/ajax-edit', 'ground_id' => $model['id']], '关联', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                </li>
            </ul>
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
                                        'delete', 'ground_id' => $model['ground_id'], 'house_id' => $model['house_id']
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
$url = Url::to(["/member-base/house-map/delete-all", 'ground_id' => $model['id']]);
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

<?php

use common\enums\AuditEnum;
use common\enums\StatusEnum;
use common\enums\VerifyEnum;
use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\helpers\BaseHtml;
use common\helpers\ImageHelper;
use common\models\monitor\project\house\Report;
use yii\helpers\Url;

$this->title = '派件详情';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>


<div class="row">
    <div class="col-xs-12 col-md-4 col-lg-4">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">概况</h3>
                <div class="box-tools">
                    <?= Html::edit(['ajax-edit', 'id' => $model['id']], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                    
                    <?= Html::linkButton(['remind','id' => $model['id']],'<i class="fa fa-bullhorn" aria-hidden="true"></i>消息通知') ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <td>关联项目</td>
                            <td>
                                <?= $model['item']['title']?Html::a($model['item']['title'], ['/monitor-project/item/view','id' => $model['pid']], $options = [
                                    'class' => 'openContab'
                                ]):'未关联项目' ?>
                            </td>
                            <td>状态</td>
                            <td>       
                                <?= VerifyEnum::html($model['audit']) ?>
                                <?= Html::a('<i class="fa fa-pencil-square-o" aria-hidden="true"></i>', ['ajax-audit', 'id' => $model['id']], $options = [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg'
                                ]) ?></td>

                        </tr>
                        <tr>
                            <td>发布者</td>
                            <td><?= $model['user']?$model['user']['realname']:'管理员' ?></td>
                            <td>负责人</td>
                            <td><?= $model['member']['realname'] ?></td>
                        </tr>
                        <tr>

                            <td>开始时间</td>
                            <td><?= date('Y-m-d', $model['start_time']) ?></td>
                            <td>截止时间</td>
                            <td><?= date('Y-m-d', $model['end_time']) ?></td>
                        </tr>
                        <tr>
                            <td>备注</td>
                            <td colspan="3">
                                <?= $model['description'] ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-header">
                <h3 class="box-title">审核记录</h3>
            </div>
            <div class="box-body table-responsive">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <th>时间</th>
                            <th>说明</th>
                            <th>备注</th>
                        </tr>
                        <?php foreach ($model->auditLog ?: [] as $key => $value) : ?>
                            <tr>
                                <td><?= date('Y-m-d', $value['created_at']) ?></td>
                                <td><?= $value['remark'] ?></td>
                                <td><?= $value['description'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">关联建筑物</h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-service/service-map/house-list', 'pid' => $model['id']], '关联', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                     <?= Html::create(['/monitor-service/service/house-ground', 'id' => $model['id']], '添加组', [
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
                                return Html::a($model['house']['title'], ['/monitor-project/house/view', 'id' => $model['house']['id']], $options = ['class' => 'openContab']);
                            },
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'images',
                            'value' => function ($model) {
                                return ImageHelper::fancyBoxs($model['images']); 
                            },
                            'format' => 'raw'

                        ],
                        'description',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {report} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['/monitor-service/service-map/view', 'id' => $model->id], '详情');
                                },
                                'report' => function ($url, $model, $key) {
                                    return BaseHtml::edit(['/monitor-service/service-map/ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                        'class' => 'blue'
                                    ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::a('删除', ['/monitor-service/service-map/delete', 'id' => $model->id], [
                                        'class' => 'red',
                                        'onclick' => "rfDelete(this);return false;"
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
$url = Url::to(["/monitor-service/service-map/delete-all"]);

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

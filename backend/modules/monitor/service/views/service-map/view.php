<?php

use common\enums\AuditEnum;
use common\enums\VerifyEnum;
use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\helpers\BaseHtml;
use common\helpers\ImageHelper;
use yii\helpers\Url;

$this->title = '派件详情';
$this->params['breadcrumbs'][] = ['label' => '任务列表', 'url' => ['/monitor-service/service/index']];
$this->params['breadcrumbs'][] = ['label' => '任务详情', 'url' => ['/monitor-service/service/view','id' => $model['pid']]];
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
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <td>房屋</td>
                            <td><?= Html::a($model['house']['title'], ['/monitor-project/house/view','id' => $model['map_id']], $options = [
                                'class' => 'openContab'
                            ]) ?></td>
                        </tr>
                        <tr>
                            <td>备注</td>
                            <td><?= $model['description'] ?></td>
                        </tr>
                        <tr>
                            <td>照片</td>
                            <td><?= ImageHelper::fancyBoxs($model->images) ?></td>
                        </tr>
                        <tr>
                            <td>反馈文件</td>
                            <td>
                                <?php foreach ($model->files?:[] as $key => $value): ?>
                                    <a href="<?= $value ?>" download="download">点击下载</a>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>反馈</td>
                            <td><?= $model['description'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-8 col-lg-8">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">关联报告</h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-service/service-map/ajax-report', 'id' => $model['id']], '关联', [
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
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'points',
                            'headerOptions' => ['width' => '40px'],
                        ],
                        'report.file_name',
                        [
                            'attribute' => 'report.verify',
                            'value' => function ($queue) {
                                return VerifyEnum::html($queue['report']['verify']);
                            },
                            'filter' => VerifyEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        'report.description',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {report} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::linkButton(['/monitor-project/report/view','id' => $model['report_id']],'详情',[
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::a('取消关联', ['delete-report','service_id' => $model['service_id'],'report_id' => $model['report_id']], [
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
$url = Url::to(["del-reports",'id' => $model['id']]);

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

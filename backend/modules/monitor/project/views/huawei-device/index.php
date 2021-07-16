<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-07 11:07:54
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 11:21:05
 * @Description: 
 */

use common\enums\AxisEnum;
use common\enums\NewsEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\Url;

$this->title = '关联设备';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['/monitor-project/house/index']];
$this->params['breadcrumbs'][] = ['label' => $pointModel->house->title, 'url' => ['/monitor-project/house/view','id' => $pointModel->house->id]];
$this->params['breadcrumbs'][] = ['label' => $pointModel->title, 'url' => ['/monitor-project/point/view','id' => $pointModel->id]];
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<script>
    function func() {
        var form = document.getElementById("ruleForm"); //获取form表单对象
        form.submit(); //form表单提交
    };
</script>
<div class="alert alert-warning" role="alert">倾斜设备为华为云，裂缝设备为阿里云</div>
<div class="row">
    <div class="col-xs-12">
    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">华为云设备</a></li>
                <li><a href="<?= Url::to(['/monitor-project/ali-device/index', 'pid' => $pointModel->id]) ?>"> 阿里设备</a></li>
                <li class="pull-right">
                    <?= Html::create(['edit', 'point_id' => $pointModel->id], '安装', [
                    ]); ?>
                </li>
            </ul>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'header' => '设备名称',
                            'value' => 'device.device_name',
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        'device.number',
                        [
                            'attribute' => 'install_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        [
                            'header' => '对应坐标轴',
                            'value' => function ($model) {
                                return AxisEnum::getValue($model->axis);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '朝向',
                            'value' => function ($model) {
                                return NewsEnum::getValue($model->news);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {edit} {status} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id], '编辑', [
                                    ]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

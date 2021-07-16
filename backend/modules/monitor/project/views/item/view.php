<?php

use common\enums\NewsEnum;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\helpers\Url;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\helpers\Html;
use yii\helpers\Html as BaseHtml;
use common\helpers\ImageHelper;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '项目列表', 'url' => Url::to(['/monitor-project/item/index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <div class="box-header">
                <i class="fa fa-clone blue" style="font-size: 8px"></i>
                <h3 class="box-title">项目详情</h3>
            </div>
            <div class="box-body table-responsive">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <td>项目名称</td>
                            <td><?= $model['title'] ?></td>
                            <td>户主信息</td>
                            <td><?= $model['hold']?:"未设置" ?></td>
                            <td>联系方式</td>
                            <td><?= $model['mobile']?:"未设置" ?></td>
                        </tr>
                        <tr>
                            <td>描述</td>
                            <td><?= $model['description'] ?: '未设置' ?></td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-header">
                <i class="fa fa-list blue" style="font-size: 8px"></i>
                <h3 class="box-title">房屋列表</h3>
            </div>
            <div class="box-body table-responsive">
                <?php Pjax::begin(); ?>
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'cover',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if (preg_match("/images/", $model['cover'])) {
                                    return ImageHelper::fancyBox($model->cover);
                                }

                                return BaseHtml::a('预览', $model->cover, [
                                    'target' => '_blank'
                                ]);
                            },
                            'format' => 'raw'
                        ],
                        [
                            'attribute' => 'title',
                            'value' => function ($queue) {
                                return Html::a($queue['title'], ['/monitor-project/house/view', 'id' => $queue['id']], $options = ['class' => 'openContab']);
                            },
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        'address',
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{status} {destroy}',
                            'buttons' => [
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['/monitor-project/house/destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

                <?php Pjax::end(); ?>
            </div>
        </div>
    </div>
</div>
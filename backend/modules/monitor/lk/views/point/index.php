<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-02-23 16:07:08
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-25 09:29:06
 * @Description: 
 */

use common\enums\WarnEnum;
use common\helpers\BaseHtml;
use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use yii\widgets\ActiveForm;

$this->title = '设备列表';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">

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
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'points',
                        ],
                        [
                            'attribute' => 'house.title',
                            'value' => function ($model) {
                                return Html::a($model['house']['title'], ['/monitor-project/house/view', 'id' => $model['house']['id']], [
                                    'class' => 'openContab'
                                ]) . ' - ' . Html::a($model['point']['title'], ['/monitor-project/point/view', 'id' => $model['point_id']], [
                                    'class' => 'openContab'
                                ]);
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'device.number',
                            'value' => function ($model) {
                                return $model['device']['number'] . ' ' . $model['device']['deviceStatus'] . ' ' . $model['device']['deviceVoltage'];
                            },
                            'format' => 'html',
                        ],
                        [
                            'header' => "是否报警",
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[Yii::$app->services->pointWarn->getPointWarn($model['point_id'])];
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html'
                        ],
                        [
                            'header' => "最新数据",
                            'value' => function ($model) {
                                return $model['value']['value'];
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'device.last_time',
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::edit(['view', 'id' => $model->id], '查看');
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['destroy', 'id' => $model->id]);
                                },
                            ]
                        ]
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
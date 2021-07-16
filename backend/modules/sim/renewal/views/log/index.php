<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 14:51:07
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 16:55:08
 * @Description: 
 */

use yii\grid\GridView;
use common\helpers\Html;

$this->title = '续费记录';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'rowOptions' => function ($model, $key, $index, $grid) {
                        return ['class' => $index % 2 == 0 ? $key : 'label-green'];
                    },
                    'columns' => [
                        'user.username',
                        'card.iccid',
                        'day',
                        [
                            'attribute' => 'expiration_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{destroy}',
                            'buttons' => [
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ]
                ]); ?>

            </div>
        </div>
    </div>
</div>
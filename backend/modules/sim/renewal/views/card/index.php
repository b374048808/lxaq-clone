<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 14:51:07
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 15:31:39
 * @Description: 
 */

use common\enums\CardEnum;
use yii\grid\GridView;
use common\helpers\Html;

$this->title = '续费';
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
                        [
                            'attribute' => 'iccid',
                            'value' => function ($model, $key, $index, $column) {
                                return Html::a($model['iccid'], ['view','id' => $model['id']], $options = []);
                            },
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'type',
                            'value' => function ($model, $key, $index, $column) {
                               
                                return CardEnum::getValue($model['type']);
                            },
                            'filter' => CardEnum::getMap(),
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'package',
                            'value' => function ($model, $key, $index, $column) {
                               
                                return CardEnum::getPackageValue($model['package']);
                            },
                            'filter' => CardEnum::getPackageMap(),
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'operator',
                            'value' => function ($model, $key, $index, $column) {
                               
                                return CardEnum::getOperatorValue($model['operator']);
                            },
                            'filter' => CardEnum::getOperatorMap(),
                            'format' => 'html',
                        ],
                        [
                            'header' => '距离到期时间',
                            'value' => function($queue){
                                $between = $queue['expiration_time']-time();
                                $span = $between>0?'':'已过期';
                                $day = abs(round($between/86400));
                                $label = '';
                                if ($day<3 || $between<0) {
                                    $label = '<span class="label label-danger">'.$span.$day.'天'.'</span>';
                                }elseif ($day<7) {
                                    $label = '<span class="label label-warning">'.$span.$day.'天'.'</span>';
                                }else{
                                    $label = '<span class="label label-primary">'.$span.$day.'天'.'</span>';
                                }
                                return $label;
                            },
                            'format' => 'html',
                            'filter' => false, //不显示搜索框,
                        ],
                        [
                            'attribute' => 'expiration_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{renewal}',
                            'buttons' => [
                                'renewal' => function ($url, $model, $key) {
                                    return Html::edit(['renewal', 'id' => $model->id], '续费', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                            ],
                        ],
                    ]
                ]); ?>

            </div>
        </div>
    </div>
</div>
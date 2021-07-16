<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-14 15:38:59
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 16:08:01
 * @Description: 
 */

use common\enums\monitor\SubscriptionActionEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\enums\SubscriptionReasonEnum;
use common\enums\ValueStateEnum;
use common\helpers\Url;

$this->title = '提醒列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => true, // 不显示#
                        ],
                        [
                            'attribute' => 'action',
                            'value' => function ($model) {
                                return SubscriptionActionEnum::$listExplain[$model['action']];
                            },
                            'filter' => SubscriptionActionEnum::getMap(),
                            'format' => 'html',
                        ],
                        'content',
                        [
                            'label' => '创建时间',
                            'attribute' => 'created_at',
                            'headerOptions' => ['class' => 'col-md-2'],
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => '操作',
                            'class' => 'yii\grid\ActionColumn',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'template' => '{view} {delete}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    $url = '';
                                    switch ($model['action']) {
                                        case SubscriptionActionEnum::ALI_OFFLINE:
                                            $url = Url::to(['/console-ali/device/view', 'id' => $model['target_id']], $schema = true);
                                            break;
                                        case SubscriptionActionEnum::HUAWEI_OFFLINE:
                                            $url = Url::to(['/console-huawei/device/view', 'id' => $model['target_id']], $schema = true);
                                            # code...
                                            break;
                                        case SubscriptionActionEnum::OVER_TIME:
                                            $url = Url::to(['/sim-list/card/index'], $schema = true);
                                            # code...
                                            break;
                                        case SubscriptionActionEnum::VALUE_WARN:
                                            $url = Url::to(['/monitor-data/value/index', 'state' => ValueStateEnum::AUDIT], $schema = true);
                                            # code...
                                            break;

                                        default:
                                            # code...
                                            break;
                                    }
                                    return Html::a('查看', $url, $options = [
                                        'class' => 'blue',
                                    ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id],'删除',[
                                    'class' => 'red'
                                ]);
                                }

                            ]
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
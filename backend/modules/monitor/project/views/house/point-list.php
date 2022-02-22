<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 10:26:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-27 14:49:43
 * @Description: 
 */

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\PointEnum;
use common\enums\WarnEnum;
use common\models\monitor\project\House;
use yii\helpers\Url;

$this->title = House::getTitle($id);
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => Url::to(['/monitor-project/house/index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['/monitor-project/house/view', 'id' => $id], $schema = true) ?>">概况</a></li>
                <li class="active"><a href="#">监测点</a></li>
                <li><a href="<?= Url::to(['/monitor-project/house/data-chart', 'id' => $id], $schema = true) ?>">数据曲线</a></li>
                <li><a href="<?= Url::to(['/monitor-project/house/report', 'id' => $id], $schema = true) ?>">报告</a></li>
                <li class="pull-right">
                    <?= Html::create(['/monitor-project/point/ajax-edit', 'pid' => $id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
            <div class="box-body table-responsive">
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
                            'attribute' => 'title',
                            'value' => function ($que) {
                                return Html::a($que['title'],['/monitor-project/point/view', 'id' => $que->id], $options = []);
                            },
                            'format' => 'html',
                        ],
                        [
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return PointEnum::getMap()[$que->type];
                            },
                            'filter' => PointEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '最新数据',
                            'attribute' => 'value',
                            'value' => function ($que) {
                                return $que['newValue']['value'];
                            },
                            'format' => 'html',
                        ],
                        [
                            'header' => "是否报警",
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[Yii::$app->services->pointWarn->getPointWarn($model['id'])];
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html'
                        ],
                        [
                            'header' => '更新时间',
                            'attribute' => 'event_time',
                            'value' => function ($que) {
                                return $que['newValue']['value']?date('Y-m-d H:i:s', $que['newValue']['event_time']):'(未更新)';
                            },
                            'format' => 'html',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{add} {destroy}',
                            'buttons' => [
                                'add' => function ($url, $model, $key) {
                                    return Html::a('添加数据',['/monitor-project/point-value/ajax-edit', 'pid' => $model->id],[
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['/monitor-project/point/destroy', 'id' => $model->id],'删除',['class' => 'red']);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
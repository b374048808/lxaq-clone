<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:31:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 17:52:22
 * @Description: 
 */

use common\enums\WarnEnum;
use common\helpers\BaseHtml;
use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '房屋';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit'], '创建',[
                        'class' => 'btn btn-primary btn-sm'
                    ]) ?>
                    <?= Html::linkButton(['excel-file'], '<i class="fa fa-cloud-upload"></i> 导入表格', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                    
                    <?= Html::linkButton(['download'], '<i class="fa fa-cloud-download"></i> 下载模板'); ?>
                    <?= Html::linkButton(['recycle'], '<i class="fa fa-trash"></i> 回收站'); ?>
                </div>
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
                        ],
                        [
                            'attribute' => 'cover',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return preg_match("/images/", $model['cover'])
                                    ? ImageHelper::fancyBox($model->cover,20,20)
                                    : '未设置';
                            },
                            'format' => 'raw'
                        ],
                        [
                            'attribute' => 'title',
                            'filter' => true, //不显示搜索框
                            'value' => function ($model) {
                                return Html::a($model['title'], ['view','id' => $model['id']], $options = []);
                            },
                            'format' => 'html'
                        ],
                        'address',
                        [
                            'header' => "是否报警",
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[Yii::$app->services->pointWarn->getHouseWarn($model['id'])];
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html'
                        ],
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
                                'status'  => function ($url, $model, $key) {
                                    return BaseHtml::status($model['status']);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-08 14:15:39
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-14 15:33:18
 * @Description: 
 */

use common\enums\VerifyEnum;
use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

$this->title = '任务列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header"> 
                <h3 class="box-title"><?= $this->title; ?></h3>
               
                <div class="box-tools">
                    <?= Html::linkButton(['/monitor-service/service-verify/index'],'审核记录') ?>
                    <?= Html::create(['ajax-edit'], '创建',[
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                    <?= Html::linkButton(['recycle'], '<i class="fa fa-trash"></i> 回收站'); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
            <div class="col-sm-12 normalPaddingJustV">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['index']),
                        'method' => 'get'
                    ]); ?>
                    <div class="row">
                        <div class="col-sm-4 p-r-no-away">
                            <div class="input-group drp-container">
                                <?= DateRangePicker::widget([
                                    'name' => 'queryDate',
                                    'value' => $from_date . '-' . $to_date,
                                    'readonly' => 'readonly',
                                    'useWithAddon' => true,
                                    'convertFormat' => true,
                                    'startAttribute' => 'from_date',
                                    'endAttribute' => 'to_date',
                                    'startInputOptions' => ['value' => $from_date?date('Y-m-d',$from_date):date('Y-m-d',strtotime('-1 month'))],
                                    'endInputOptions' => ['value' => $to_date?date('Y-m-d',$to_date):date('Y-m-d')],
                                    'pluginOptions' => [
                                        'locale' => ['format' => 'Y-m-d'],
                                    ]
                                ]) . $addon;?>
                            </div>
                        </div>
                        <div class="col-sm-3 p-l-no-away">
                            <div class="input-group m-b">
                                <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
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
                            'header'    => '关联项目',
                            'attribute' => 'item.title'
                        ],
                        [
                            'header'    => '发布者',
                            'attribute' => 'user.realname'
                        ],
                        [
                            'header'    => '负责人',
                            'attribute' => 'member.realname'
                        ],
                        [
                            'header' => '审核状态',
                            'attribute' => 'audit',
                            'value' => function($model){
                                $str = '';
                                if ($model['audit'] < VerifyEnum::WAIT) {
                                    if(time() > $model['end_time'])
                                    $str =  '<span class="label label-warning">超时未完成</span>';
                                    if (time() < $model['start_time']) {
                                        $str =  '<span class="label label-info">未开始</span>';
                                    }
                                }
                                return VerifyEnum::html($model['audit']).'  '.$str;
                            },
                            'format' => 'html',
                            'filter' => VerifyEnum::getMap(),
                            'headerOptions' => ['width' => '160px']
                        ],
                        [
                            'header' => '起止时间',
                            'value' => function($model){
                                return $model['end_time']?date('Y-m-d',$model['start_time']).'~'.date('Y-m-d',$model['end_time']):'未定义';
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html'
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {status} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看');
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
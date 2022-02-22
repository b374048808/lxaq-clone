<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-08 14:15:39
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 15:40:59
 * @Description: 
 */

use common\enums\AuditEnum;
use common\enums\VerifyEnum;
use yii\grid\GridView;
use common\helpers\BaseHtml as Html;
use common\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

$this->title = '审核记录';
$this->params['breadcrumbs'][] = ['label' => '任务列表','url' => ['/monitor-service/service/index']];
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
                                    'startInputOptions' => ['value' => date('Y-m-d',$from_date)],
                                    'endInputOptions' => ['value' => date('Y-m-d',$to_date)],
                                    'pluginOptions' => [
                                        'locale' => ['format' => 'Y-m-d'],
                                    ]
                                ]) . $addon;?>
                            </div>
                        </div>
                        <div class="col-sm-3 p-l-no-away">
                            <div class="input-group m-b">
                                <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>                 
                                <?= Html::a('导出表格', ['export','from_date' => $from_date,'to_date' => $to_date], $options = [
                                    'class' => 'btn btn-white'
                                ]) ?>
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
                            'header'    => '任务负责人',
                            'attribute' => 'member.realname'
                        ],
                        [
                            'header'    => '提交人',
                            'attribute' => 'user.realname'
                        ],
                        [
                            'attribute' => 'verify',
                            'value' => function($model){
                                return VerifyEnum::html($model['verify']);
                            },
                            'format' => 'html',
                            'filter' => AuditEnum::getMap(),
                            'headerOptions' => ['width' => '120px']
                        ],
                        'remark',
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
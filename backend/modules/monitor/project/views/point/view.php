<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 11:36:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-10 15:12:36
 * @Description: 
 */

use common\enums\device\SwitchEnum;
use common\enums\monitor\WarnTypeEnum;
use common\enums\NewsEnum;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\helpers\Url;
use common\helpers\Html;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\helpers\ImageHelper;
use yii\grid\GridView;
use common\enums\ValueStateEnum;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;
use common\models\monitor\project\point\Value;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => Url::to(['/monitor-project/item/index'])];
$this->params['breadcrumbs'][] = ['label' => $model->house->title, 'url' => Url::to(['/monitor-project/house/view', 'id' => $model['pid']], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>
<style type="text/css">
    body,
    html,
    #allmap {
        width: 100%;
        height: 400px;
        /* overflow: hidden; */
        margin: 0;
    }
</style>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title"><?= $this->title ?></h3>

                <?php if ($model->warnState) : ?>
                    <?= Html::linkButton(['/monitor-project/warn/view', 'id' => $model->warnState['id']], '<i class="fa fa-bell" ></i> ' . WarnEnum::getValue($model->warnState['warn']), [
                        'class' => 'btn btn-danger btn-xs',
                    ]) ?>
                <?php else : ?>
                    <?= Html::linkButton(['/monitor-project/warn/ajax-edit', 'pid' => $model['id']], '<i class="fa fa-bell"></i> 发起报警', [
                        'class' => 'btn btn-warning btn-xs',
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                <?php endif; ?>
                <a href="<?= Url::to(['ajax-edit', 'id' => $model['id']], $schema = true) ?>" data-toggle='modal' , data-target='#ajaxModalLg' ,>
                    <i class="fa fa-edit blue" style="font-size: 12px"></i>
                </a>
                <div class="box-tools">
                    <?= Html::linkButton(['/monitor-project/warn/ajax-list', 'pid' => $model['id']], '报警历史记录', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                    <?= Html::linkButton(['data-chart', 'id' => $model['id']], '数据曲线') ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>点位类型</td>
                        <td><?= PointEnum::getMap()[$model['type']] ?></td>
                        <td>初始数据</td>
                        <td><?= $model['initial_value'] ?: '未设置' ?></td>
                        <td>状态</td>
                        <td><?= StatusEnum::getValue($model['status']) ?></td>
                    </tr>
                    <tr>
                        <td>报警开关</td>
                        <td><?= SwitchEnum::getValue($model['warn_switch']) ?></td>
                        <td>报警类型</td>
                        <td><?= WarnTypeEnum::getValue($model['warn_type']) ?></td>
                        <td>类型说明</td>
                        <td><?= WarnTypeEnum::getDesc($model['warn_type']) ?></td>
                    </tr>
                    <tr>
                        <td>朝向</td>
                        <td><?= NewsEnum::getValue($model['news']) ?: '未设置' ?></td>
                        <td>图像</td>
                        <td>
                            <?php if (is_array($model['covers'])) : ?>
                                <?php foreach ($model['covers'] as $value) : ?>
                                    <?= ImageHelper::fancyBox($value, 20, 20)  ?>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">绑定设备</h3>

                <div class="box-tools">
                    <?= Html::linkButton(['ajax-device', 'point_id' => $model['id'], 'id' => $model['deviceMap']['id']], '<i class="icon ion-link"></i> 绑定设备', [
                        'class' => 'btn btn-info btn-xs',
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>设备编号</td>
                        <td><?= $model['device']['number'] ?></td>
                        <td>数据</td>
                        <td><?= $model['newValue']['value'] ?></td>
                        <td>最后上线时间</td>
                        <td><?= $model['device']['last_time'] ? date('Y-m-d H:i:s', $model['device']['last_time']) : '' ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">历史数据</h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-project/point-value/ajax-edit', 'pid' => $model['id']], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                        'class' => 'btn btn-white btn-sm'
                    ]) ?>
                    <?= Html::linkButton(['/monitor-project/point-value/value-rand', 'id' => $model['id']], '<i class="fa fa-random"></i> 生成数据', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                    <?= Html::linkButton(['/monitor-project/point-value/export', 'id' => $model['id'], 'from_date' => $from_date, 'to_date' => $to_date, 'type' => $type, 'warn' => $warn, 'pid' => $pointModel->id], '<i class="fa fa-upload"></i> 导出Excel'); ?>
                    <?= Html::linkButton(['/monitor-project/point-value/excel-file', 'pid' => $model['id']], '<i class="fa fa-cloud-upload"></i> 批量上传', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>

                </div>
            </div>
            <div class="box-body">
                <?php $form = ActiveForm::begin([
                    'action' => Url::to(['view', 'id' => $model['id']]),
                    'method' => 'get'
                ]); ?>

                <div class="row">
                    <div class="col-sm-3">
                        <div class="input-group drp-container">
                            <?= DateRangePicker::widget([
                                'name' => 'queryDate',
                                'value' => $from_date . '-' . $to_date,
                                'readonly' => 'readonly',
                                'useWithAddon' => true,
                                'convertFormat' => true,
                                'startAttribute' => 'from_date',
                                'endAttribute' => 'to_date',
                                'startInputOptions' => ['value' => $from_date ?: date('Y-m-d', strtotime("-6 day"))],
                                'endInputOptions' => ['value' => $to_date ?: date('Y-m-d')],
                                'pluginOptions' => [
                                    'locale' => ['format' => 'Y-m-d'],
                                ]
                            ]) . $addon; ?>

                        </div>
                    </div>
                    <div class="col-sm-3">
                        <?= Html::tag('span', '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
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
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => true, // 不显示#
                        ],
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'values',
                            'headerOptions' => ['width' => '80px']
                        ],
                        [
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'attribute' => 'value',
                            'value' => function ($model) {
                                $num = round($model['value'] - Value::getPrevValue($model['id']), 4);
                                return $num  > 0
                                    ? $model['value'] . '<i class="fa fa-long-arrow-up red">' . $num . '</i>'
                                    : $model['value'] . '<i class="fa fa-long-arrow-down blue">' . $num . '</i>';
                            },
                            'filter' => false, //显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return ValueTypeEnum::getValue($que->type);
                            },
                            'filter' => ValueTypeEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => "报警等级",
                            'attribute' => 'warn',
                            'value' => function ($model) {
                                return WarnEnum::$spanlistExplain[$model->warn];
                            },
                            'filter' => WarnEnum::getMap(),
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'state',
                            'value' => function ($model) {
                                return Html::a(
                                    ValueStateEnum::getValue($model['state']),
                                    ['/monitor-project/point-value/ajax-state', 'id' => $model->id, 'type' => PointEnum::ANGLE],
                                    [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                        'class' => '' . ($model['state'] == ValueStateEnum::AUDIT ? 'text-primary ' : 'text-white'),
                                    ]
                                );
                            },
                            'filter' => ValueStateEnum::getMap(),
                            'format' => 'raw',
                        ],
                        [
                            'label' => '状态',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return ValueStateEnum::getValue($model->state);
                            },
                            'format' => 'raw',
                        ],

                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {state} {edit} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['/monitor-project/point-value/view', 'id' => $model->id], '查看', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['/monitor-project/point-value/ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['/monitor-project/point-value/destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <?= Html::a('删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>
                <?= Html::linkButton(['/monitor-project/point-value/recyle', 'pid' => $model['id'], 'type' => $pointModel['type']], '<i class="fa fa-trash"></i> 回收站', [
                    'style' => 'float:right',
                ]); ?>

            </div>
        </div>
    </div>

</div>
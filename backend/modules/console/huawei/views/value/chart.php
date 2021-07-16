<?php

use common\helpers\Html;
use common\helpers\Url;
use kartik\daterange\DateRangePicker;
use yii\bootstrap\ActiveForm;
use yii\grid\GridView;

$this->title = '历史图表';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>
<?= Html::jsFile('@web/resources/plugins/echarts/echarts.min.js') ?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">历史数据</a></li>
                <li><a href="<?= Url::to(['chart-real','pid' => $pid, 'type'=>$type, 'service' => $service], $schema = true) ?>">实时数据</a></li>
            </ul>
            <div class="box-body table-responsive">
                <div class="row">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['chart', 'pid' => $pid, 'service' => $service, 'type' => $type]),
                        'method' => 'get'
                    ]); ?>
                    <div class="col-sm-6">
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
                    <div class="col-sm-4">
                        <?= Html::submitButton('<i class="fa fa-search"></i>搜索', ['class' => 'btn btn-white']) ?>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <table class="table" style="margin-top: 20px;">
                    <tr>
                        <td colspan="4">
                            <div id="main" style="width: 100%;height:400px;"></div>
                        </td>
                    </tr>
                </table>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'value.'.$type,
                            'filter' => false, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'event_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{delete}',
                            'buttons' => [
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
        </div>

    </div>
</div>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'), 'macarons');

    function chartOption(series, legend, chartTime, unit = "mm") {
        var sevices = [];
        for (let index = 0; index < legend.length; index++) {
            sevices.push({
                name: legend[index],
                type: 'line',
                smooth: true,
                showSymbol: true,
                connectNulls: true,
                data: series
            });
        }

        var option = {
            title: {
                text: '数据变化',
                subtext: '单位 ' + unit
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: legend
            },
            toolbox: {
                show: true,
                feature: {
                    dataZoom: {
                        yAxisIndex: 'none'
                    },
                    mark: {
                        show: true
                    },
                    dataView: {
                        show: true,
                        readOnly: false
                    },
                    magicType: {
                        show: true,
                        type: ['line', 'bar', 'stack', 'tiled']
                    },
                    restore: {
                        show: true
                    },
                    saveAsImage: {
                        show: true
                    }
                }
            },
            calculable: true,
            xAxis: [{
                type: 'category',
                boundaryGap: false,
                data: chartTime
            }],
            yAxis: [{
                type: 'value',
                axisLabel: {
                    formatter: function(value, index) {
                        value = parseFloat(value);
                        return value.toFixed(4);
                    }
                }
            }],
            series: sevices
        };
        return option;
    }
    myChart.setOption(chartOption(
        <?= json_encode($info['data']) ?>,
        <?= json_encode($info['legend']) ?>,
        <?= json_encode($info['time']) ?>, '‰')); // 加载图表
</script>
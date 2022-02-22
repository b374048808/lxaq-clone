<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-08 11:36:29
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-21 15:21:43
 * @Description: 
 */

use common\enums\TimeUnitEnum;
use common\helpers\Html;
use common\models\monitor\project\House;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

$this->title = '数据曲线';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => House::getTitle($id), 'url' => ['view', 'id' => $id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];

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
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">数据曲线</h3>
            </div>
            <div class="box-body table-responsive">
                <div class="col-sm-12 normalPaddingJustV">
                    <?php $form = ActiveForm::begin([
                        'method' => 'get'
                    ]); ?>
                    <div class="row">
                        <div class="col-sm-1">
                            <?= Html::dropDownList('unit', $selection = Yii::$app->request->get('unit'), $items = TimeUnitEnum::getMap(), $options = [
                                'class' => 'form-control select2-hidden-accessible'
                            ]) ?>
                        </div>
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
                                    'startInputOptions' => ['value' => $from_date],
                                    'endInputOptions' => ['value' => $to_date],
                                    'pluginOptions' => [
                                        'locale' => ['format' => 'Y-m-d'],
                                    ]
                                ]) . $addon; ?>
                            </div>
                        </div>
                        <div class="col-sm-3">
                            <div class="input-group m-b">
                                <?= Html::tag(
                                    'span',
                                    '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>',
                                    ['class' => 'input-group-btn']
                                ) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <div class="col-sm-12">
                    <div id="main" style="width: 100%;height:400px;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'), 'macarons');
    myChart.setOption(chartOption(
        <?= json_encode($info['series']) ?>,
        <?= json_encode($info['legend']) ?>,
        <?= json_encode($info['chartTime']) ?>,
        '<?= TimeUnitEnum::getValue(Yii::$app->request->get('unit')) ?>'
    ));

    function chartOption(series, legend, chartTime, unit = "mm") {
        var sevices = [];
        console.log(chartTime);
        for (let index = 0; index < series.length; index++) {
            sevices.push({
                name: series[index].name,
                type: 'line',
                smooth: true,
                showSymbol: true,
                connectNulls: true,
                data: series[index].data
            });
        }
        var option = {
            title: {
                text: '历史数据曲线',
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
                        return value.toFixed(2);
                    }
                }
            }],
            dataZoom: [{
                    type: 'inside',
                    start: 0,
                    end: 100
                },
                {
                    start: 0,
                    end: 10,
                    handleIcon: 'M10.7,11.9v-1.3H9.3v1.3c-4.9,0.3-8.8,4.4-8.8,9.4c0,5,3.9,9.1,8.8,9.4v1.3h1.3v-1.3c4.9-0.3,8.8-4.4,8.8-9.4C19.5,16.3,15.6,12.2,10.7,11.9z M13.3,24.4H6.7V23h6.6V24.4z M13.3,19.6H6.7v-1.4h6.6V19.6z',
                    handleSize: '80%',
                    handleStyle: {
                        color: '#fff',
                        shadowBlur: 3,
                        shadowColor: 'rgba(0, 0, 0, 0.6)',
                        shadowOffsetX: 2,
                        shadowOffsetY: 2
                    }
                }
            ],
            series: sevices
        };
        return option;
    }
</script>
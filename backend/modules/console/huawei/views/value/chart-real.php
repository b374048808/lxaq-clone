<?php

use common\enums\NewsEnum;
use common\helpers\Url;
use common\helpers\Html;
use common\enums\PointEnum;
use yii\grid\GridView;
use common\helpers\ImageHelper;
use common\enums\StructEnum;
use yii\widgets\Pjax;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<?= Html::jsFile('@web/resources/plugins/echarts/echarts.min.js') ?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['chart', 'pid' => $pid, 'type' => $type, 'service' => $service], $schema = true) ?>">历史数据</a></li>
                <li class="active"><a href="#">实时数据</a></li>
            </ul>
            <div class="box-body">
                <div class="box-header">
                    <i class="fa fa-circle blue" style="font-size: 8px"></i>
                    <h3 class="box-title">数据曲线图</h3>
                    <div class="box-tools">
                    </div>
                </div>
                <div class="box-body table-responsive">
                    <table class="table">
                        <tr>
                            <td colspan="4">
                                <div id="main" style="width: 100%;height:400px;"></div>
                            </td>
                        </tr>
                    </table>
                    <table class="table" id="content-table">
                        <tr>
                            <th>时间</th>
                            <th>数据</th>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>


    <script type="text/javascript">
        var startTime = '<?= $info['startTime'] ?>';
        var myChart = echarts.init(document.getElementById('main'));
        var currentOutSpeed = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        var currentInputSpeed = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
        var chartTime = <?= json_encode($info['chartTime']) ?>;
        var legend = <?= json_encode($info['legend']) ?>;
        console.log(legend);

        function chartOption() {
            var series = [];
            for (let index = 0; index < legend.length; index++) {
                series.push({
                    name: legend[index],
                    type: 'line',
                    smooth: true,
                    itemStyle: {
                        normal: {
                            areaStyle: {
                                type: 'default'
                            }
                        }
                    },
                    data: currentOutSpeed
                });
            }

            var option = {
                title: {
                    subtext: '单位 <?= $info['unit'] ?>'
                },
                tooltip: {
                    trigger: 'axis'
                },
                legend: {
                    data: legend
                },
                toolbox: {
                    show: false,
                    feature: {
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
                    type: 'value'
                }],
                series: series
            };

            return option;
        }

        myChart.setOption(chartOption()); // 加载图表

        $(document).ready(function() {
            setTime();
            setInterval(setTime, 1000);
            setInterval(getServerInfo, 3000);
        });

        function setTime() {
            var d = new Date(),
                str = '';
            str += d.getFullYear() + ' 年 '; // 获取当前年份
            str += d.getMonth() + 1 + ' 月 '; // 获取当前月份（0——11）
            str += d.getDate() + ' 日  ';
            str += d.getHours() + ' 时 ';
            str += d.getMinutes() + ' 分 ';
            str += d.getSeconds() + ' 秒 ';
            $("#divTime").text(str);
        }

        function getServerInfo() {
            var data = {
                pid: <?= $pid ?>,
                startTime,
                service: '<?= $service ?>',
                type: '<?= $info['data'] ?>'
            };
            console.log(data);
            $.ajax({
                type: "get",
                url: "<?= Url::to(['chart-real']) ?>",
                dataType: "json",

                data: data,
                success: function(data) {
                    console.log(data);
                    if (data.code == 200 || data.code == 300) {
                        if (data.code == 200) {
                            var data = data.data;
                            startTime = data.startTime;

                            currentOutSpeed.shift();
                            currentOutSpeed.push(data.data);

                            chartTime.shift();
                            chartTime.push(data.startTime);
                            $("#content-table").append('<tr><td>' + data.startTime + '</td><td>' + data.data + '</td></tr>');

                            myChart.setOption(chartOption()); // 加载图表
                        }
                    } else {
                        rfAffirm(data.message);
                    }
                }
            });
        }
    </script>
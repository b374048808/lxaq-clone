<?php

use common\enums\PointEnum;
use common\helpers\Url;
use common\helpers\Html;


?>
<?= Html::jsFile('@web/resources/plugins/echarts/echarts.min.js') ?>

<div id="main_<?= $type ?>" style="height:400px;"></div>

<script type="text/javascript">
    var start_time = type_<?= $type ?> = '';
    var myChart_<?= $type ?> = echarts.init(document.getElementById('main_<?= $type ?>'), 'macarons');

    new Promise(async function(resolve, reject) {
        // var info_<?= $type ?> = setInterval(getServerInfo, 60000);
        type_<?= $type ?> = '<?= PointEnum::getValue($type) ?>';
        await getServerInfo();
        resolve();
    })



    function chartOption(series, legend, chartTime, unit = "mm") {

        console.log(type_<?= $type ?>);
        var sevices_<?= $type ?> = [];
        for (let index = 0; index < series.length; index++) {
            sevices_<?= $type ?>.push({
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
                text: type_<?= $type ?>,
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
            series: sevices_<?= $type ?>
        };
        return option;
    }

    function getServerInfo() {
        console.log('1');
        $.ajax({
            async:false,
            type: "get",
            url: "<?= Url::to(['monitor-type']) ?>",
            dataType: "json",
            data: {
                id: <?= $id ?>,
                type: <?= $type ?>,
                start_time: start_time
            },
            success: function(data) {
                console.log('3');
                if (data.code == 200) {
                    var data = data.data;
                    start_time = data.time
                    myChart_<?= $type ?>.setOption(chartOption(data.values, data.name, data.times, '‰')); // 加载图表

                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>
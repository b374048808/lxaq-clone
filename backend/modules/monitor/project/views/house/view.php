<?php
use common\helpers\Url;
use common\helpers\Html;

$this->title = '系统探针';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<?= Html::jsFile('@web/resources/plugins/echarts/echarts-all.js')?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">详情</a></li>
                <li ><a href="<?= Url::to(['/monitor-project/point/index','pid' => $model['id']]) ?>">监测点</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit', 'pid' => $pid], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
        
            
            <div class="box-body table-responsive">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">数据曲线图</h3>
            </div>
                <table class="table">
                    <tr>
                        <td>总发送</td>
                        <td id="netWork_allOutSpeed"><?= $info['netWork']['allOutSpeed'] ?></td>
                        <td>总接收</td>
                        <td id="netWork_allInputSpeed"><?= $info['netWork']['allInputSpeed'] ?></td>
                    </tr>
                    <tr>
                        <td>发送速度</td>
                        <td id="netWork_currentOutSpeed"><?= $info['netWork']['currentOutSpeed'] ?></td>
                        <td>接收速度</td>
                        <td id="netWork_currentInputSpeed"><?= $info['netWork']['currentInputSpeed'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                            <div id="main" style="width: 100%;height:400px;"></div>
                        </td>
                    </tr>
                </table>
            </div>
            
        </div>
    </div>
</div>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'));
    var currentOutSpeed = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    var currentInputSpeed = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    var chartTime = <?= json_encode($info['chartTime'])?>;

    function chartOption() {
        var option = {
            title : {
                subtext: '单位 KB/s'
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['发送速度','接收速度']
            },
            toolbox: {
                show : false,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : chartTime
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'发送速度',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:currentOutSpeed
                },
                {
                    name:'接收速度',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:currentInputSpeed
                }
            ]
        };

        return option;
    }

    myChart.setOption(chartOption()); // 加载图表

    $(document).ready(function(){
        setTime();
        setInterval(setTime, 1000);
        setInterval(getServerInfo, 3000);
    });

    function setTime() {
        var d = new Date(), str = '';
        str += d.getFullYear() + ' 年 '; // 获取当前年份
        str += d.getMonth() + 1 + ' 月 '; // 获取当前月份（0——11）
        str += d.getDate() + ' 日  ';
        str += d.getHours() + ' 时 ';
        str += d.getMinutes() + ' 分 ';
        str += d.getSeconds() + ' 秒 ';
        $("#divTime").text(str);
    }

    function getServerInfo() {
        $.ajax({
            type : "get",
            url  : "<?= Url::to(['probe'])?>",
            dataType : "json",
            data: {},
            success: function(data) {
                if (data.code == 200) {
                    var data = data.data;
                    var html = template('model',data);
                    $('#sys-hardware').html(html);
                    var html2 = template('mem',data);
                    $('#memData').html(html2);

                    var netWork = data.netWork;
                    $('#netWork_allOutSpeed').text(netWork.allOutSpeed + ' G');
                    $('#netWork_allInputSpeed').text(netWork.allInputSpeed + ' G');
                    $('#netWork_currentOutSpeed').text(netWork.currentOutSpeed + ' KB/s');
                    $('#netWork_currentInputSpeed').text(netWork.currentInputSpeed + ' KB/s');

                    currentOutSpeed.shift();
                    currentInputSpeed.shift();
                    currentOutSpeed.push(netWork.currentOutSpeed);
                    currentInputSpeed.push(netWork.currentInputSpeed);
                    chartTime = data.chartTime;
                    myChart.setOption(chartOption()); // 加载图表

                    //内存
                    var memory = data.memory;
                    var memPercent = memory.memory.usage_rate;
                    var memCachedPercent = memory.cache.usage_rate;
                    var memRealPercent = memory.real.usage_rate;
                    var hardDiskUsageRate = data.hardDisk.usage_rate;

                    memPercent = memPercent.toFixed(0);
                    memCachedPercent = memCachedPercent.toFixed(0);
                    memRealPercent = memRealPercent.toFixed(0);
                    hardDiskUsageRate = hardDiskUsageRate.toFixed(0);

                    hdSpeed = [100 - hardDiskUsageRate, hardDiskUsageRate];
                    memTotal = [100 - memPercent, memPercent];
                    memCached = [100 - memCachedPercent, memCachedPercent];
                    memRealUsed = [100 - memRealPercent, memRealPercent];
                    schedule.setOption(scheduleOption()); // 加载图表
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>
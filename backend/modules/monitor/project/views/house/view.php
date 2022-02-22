<?php

use common\enums\BellEnum;
use common\enums\device\SwitchEnum;
use common\enums\NewsEnum;
use common\enums\PointEnum;
use common\helpers\Url;
use common\helpers\Html;
use common\helpers\ImageHelper;
use common\enums\StructEnum;
use yii\grid\GridView;
use common\enums\JudgeEnum;
use common\enums\monitor\BellEnum as MonitorBellEnum;
use common\enums\monitor\BellStateEnum;
use common\enums\monitor\ReportEnum;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\helpers\BaseHtml;
use common\models\monitor\project\point\Value;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<?= Html::jsFile('@web/resources/plugins/echarts/echarts.min.js') ?>
<?= Html::jsFile('//api.map.baidu.com/api?v=2.0&ak=d98sCAYZ24VDW45rDRSNpWaI') ?>
<?= Html::jsFile('//api.map.baidu.com/api?type=webgl&v=1.0&ak=d98sCAYZ24VDW45rDRSNpWaI') ?>
<style>
    #map {
        width: 100%;
    }

    #allmap {
        width: 100%;
        height: 500px;
        overflow: hidden;
        margin: 0;
        font-family: "微软雅黑";
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">概况</h3>
                <a href="<?= Url::to(['edit', 'id' => $model['id']], $schema = true) ?>" ,>
                    <i class="fa fa-edit blue" style="font-size: 12px"></i>
                </a>
                <div class="box-tools">
                    <?= Html::linkButton(['data-chart', 'id' => $model['id']], '数据曲线') ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="col-md-8 col-xs-12" style="border-right:1px solid #f1f1f1">
                    <table class="table table-hover">
                        <tr>
                            <td>户主</td>
                            <td><?= $model->title ?></td>
                            <td>联系方式</td>
                            <td><?= $model->mobile ?></td>
                        </tr>
                        <tr>
                            <td>年代</td>
                            <td><?= $model->year ?></td>
                            <td>面积</td>
                            <td><?= $model->area ?></td>
                        </tr>
                        <tr>
                            <td>层数</td>
                            <td>
                                <?= $model->layer ?>
                            </td>
                        </tr>
                        <tr>
                            <td>性质</td>
                            <td><?= StructEnum::natureMap()[$model->nature] ?></td>
                            <td>地址</td>
                            <td><?= $model->address ?></td>
                        </tr>
                        <tr>
                            <td>朝向</td>
                            <td><?= NewsEnum::getMap()[$model->news] ?></td>
                            <td>结构类型</td>
                            <td><?= StructEnum::typeMap()[$model->type] ?></td>
                        </tr>
                        <tr>
                            <td>屋面形式</td>
                            <td><?= StructEnum::roofMap()[$model->roof] ?></td>
                            <td>楼板形式</td>
                            <td><?= StructEnum::roofMap()[$model->floor] ?></td>
                        </tr>
                        <tr>
                            <td>地下室</td>
                            <td><?= $model->basement ? '有' : '无' ?></td>
                            <td>圈梁</td>
                            <td><?= $model->beam ? '有' : '无' ?></td>
                        </tr>
                        <tr>
                            <td>构造柱</td>
                            <td><?= $model->column ? '有' : '无' ?></td>
                            <td>业主单位</td>
                            <td><?= $model->owner ?></td>
                        </tr>
                        <tr>
                            <td>监理单位</td>
                            <td><?= $model->supervision ?></td>
                            <td>地址勘查单位</td>
                            <td><?= $model->prospect ?></td>
                        </tr>
                        <tr>
                            <td>施工单位</td>
                            <td><?= $model->roadwork ?></td>
                            <td>设计单位</td>
                            <td><?= $model->design ?></td>
                        </tr>
                        <tr>
                            <td>长×宽×高</td>
                            <td><?= $model->length . '×' . $model->width . '×' . $model->height  ?></td>
                            <td>示意图</td>
                            <td>
                                <?php foreach ($model->hint_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>建筑物</td>
                            <td>
                                <?php foreach ($model->layout_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>
                            </td>
                            <td>片面图</td>
                            <td>
                                <?php foreach ($model->plan_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>倾斜点位示意图</td>
                            <td>
                                <?php foreach ($model->angle_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>
                            </td>
                            <td>沉降点位示意图</td>
                            <td>
                                <?php foreach ($model->settling_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>

                            </td>
                        </tr>
                        <tr>
                            <td>裂缝点位示意图</td>
                            <td>
                                <?php foreach ($model->cracks_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>

                            </td>
                            <td>平顶位移点位示意图</td>
                            <td>
                                <?php foreach ($model->move_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>

                            </td>
                        </tr>
                    </table>
                    <?php foreach ($points as $key => $value) : ?>
                        <div class="box box-solid">
                            <div class="box-header">
                                <i class="fa fa-area-chart blue" style="font-size: 8px"></i>
                                <h3 class="box-title"><?= PointEnum::getValue($value) ?></h3>
                            </div>
                            <div class="box-body">
                                <?= \common\widgets\echarts\Echarts::widget([
                                    'config' => [
                                        'server' => Url::to(['point-between-count', 'point_type' => $value, 'id' => $model['id']]),
                                        'height' => '315px'
                                    ]
                                ]) ?>
                                <!-- /.box-body -->
                            </div>
                            <!-- /.box -->
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="col-xs-12 col-md-4">
                    <div class="box">
                        <div class="box-header">
                            <i class="fa fa-warning blue" style="font-size: 8px"></i>
                            <h3 class="box-title">报警触发器</h3>
                            <div class="box-tools">
                                <?= Html::create(['/monitor-project/rule-item/ajax-edit', 'pid' => $model['id']], '创建', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]) ?>
                            </div>
                        </div>
                        <div class="box-body table-responsive" style="font-size:8px">
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
                                        'header' => '类型',
                                        'attribute' => 'type',
                                        'value' => function ($que) {
                                            return PointEnum::getValue($que->type);
                                        },
                                        'filter' => PointEnum::getMap(), //不显示搜索框
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'warn',
                                        'value' => function ($que) {
                                            return WarnEnum::getValue($que->warn);
                                        },
                                        'filter' => WarnEnum::getMap(), //不显示搜索框
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'judge',
                                        'value' => function ($que) {
                                            return JudgeEnum::getValue($que->judge);
                                        },
                                    ],
                                    'value',
                                    [
                                        'attribute' => 'status',
                                        'value' => function ($que) {
                                            return StatusEnum::getValue($que['status']);
                                        },
                                    ],
                                    [
                                        'header' => "操作",
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{edit} {status} {destroy}',
                                        'buttons' => [
                                            'edit' => function ($url, $model, $key) {
                                                return Html::edit(['/monitor-project/rule-item/ajax-edit', 'id' => $model->id], '编辑', [
                                                    'class' => 'blue',
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#ajaxModalLg',
                                                ]);
                                            },
                                            'destroy' => function ($url, $model, $key) {
                                                return Html::delete(['/monitor-project/rule-item/destroy', 'id' => $model->id], '删除', ['class' => 'red']);
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            <i class="fa fa-list-alt blue" style="font-size: 8px"></i>
                            <h3 class="box-title">点位数据</h3>
                            <div class="box-tools">
                                <?= Html::a('<i class="fa fa-ellipsis-h"></i>', ['/monitor-project/house/value-list', 'id' => $model['id']]) ?>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-hover" style="table-layout:fixed;font-size:8px">
                                <tr>
                                    <th>时间</th>
                                    <th>点位</th>
                                    <th>数据</th>
                                    <th>报警</th>
                                </tr>
                                <?php foreach ($valueList as $key => $value) : ?>
                                    <tr>
                                        <td><?= date('m-d H:i', $value['event_time']) . ($value['event_time'] > strtotime('-1 hour') ? ' <span class="label label-warning" style="font-size:2px">NEW</span>' : '') ?></td>
                                        <td><?= Html::a($value['parent']['title'], ['/monitor-project/point/view', 'id' => $value['pid']], $options = [
                                                'class' => 'openContab'
                                            ]) ?></td>
                                        <td>
                                            <?= $value['value'] ?>
                                            <?php if (($num = round(($value['value'] - Value::getPrevValue($value['id'])), 4)) > 0) : ?>
                                                <i class="fa fa-long-arrow-up red"><?= $num ?></i>
                                            <?php else : ?>
                                                <i class="fa fa-long-arrow-down blue"><?= $num ?></i>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?= WarnEnum::getValue($value['warn']) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            <i class="fa fa-circle blue" style="font-size: 8px"></i>
                            <h3 class="box-title">最新报告</h3>
                            <div class="box-tools">
                                <?= Html::linkButton(['report', 'id' => $model['id']], '查看更多>>', [
                                    'class' => ''
                                ]); ?>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <table class="table table-hover">
                                <tr>
                                    <th>上传人员</th>
                                    <th>类型</th>
                                    <th>文件名</th>
                                    <th>上传时间</th>
                                    <th>操作</th>
                                </tr>
                                <?php foreach ($reportModel ?: [] as $key => $value) : ?>
                                    <tr>
                                        <td><?= $value['user']['realname'] ?></td>
                                        <td><?= ReportEnum::getValue($value['type']) . $value['type'] ?></td>
                                        <td><?= $value['file_name'] ?></td>
                                        <td><?= date('Y-m-d H:i', $value['created_at']) ?></td>
                                        <td>
                                            <?= Html::a('下载', $value['file'], [
                                                'download' => 'download',
                                            ]) ?>
                                            <?= Html::a('编辑', ['/monitor-project/report/ajax-edit', 'id' => $value['id']], [
                                                'data-toggle' => 'modal',
                                                'data-target' => '#ajaxModalLg',
                                            ]) ?>
                                            <?= Html::delete(['/monitor-project/report/destroy', 'id' => $value['id']], '删除', ['class' => 'red']) ?>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12">

        <div class="box">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">监测点</h3>
                <a href="<?= Url::to(['ajax-edit', 'id' => $model['id']], $schema = true) ?>" data-toggle='modal' , data-target='#ajaxModalLg' ,>
                    <i class="fa fa-edit blue" style="font-size: 12px"></i>
                </a>
                <div class="box-tools">
                    <?= Html::create(['/monitor-project/point/ajax-edit', 'pid' => $model['id']], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>监测点名称</th>
                        <th>类型</th>
                        <th>是否报警</th>
                        <th>报警开关</th>
                        <th>绑定设备</th>
                        <th>更新时间</th>
                        <th>数据类型</th>
                        <th>最新数据</th>
                        <th>操作</th>
                    </tr>
                    <?php foreach ($pointModel ?: [] as $key => $value) : ?>
                        <tr>
                            <td><?= Html::a($value['title'], ['/monitor-project/point/view', 'id' => $value['id']], $options = []) ?></td>
                            <td><?= PointEnum::getValue($value['type']) ?></td>

                            <td><?= WarnEnum::$spanlistExplain[Yii::$app->services->pointWarn->getPointWarn($model['id'])] ?></td>
                            <td><?= SwitchEnum::getValue($value['warn_switch']) ?></td>
                            <td>
                                <?= Html::a($value['device']['number'], ['/console-huawei/device/view', 'id' => $value['device']['id']]) ?>
                                <?= Html::linkButton(['ajax-device', 'point_id' => $value['id'], 'id' => $value['deviceMap']['id']], '<i class="icon ion-link"></i> ' . '绑定设备', [
                                    'class' => 'btn btn-info btn-xs',
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ]); ?>
                            </td>
                            <td><?= $value['newValue']['event_time'] ? date('Y-m-d H:i', $value['newValue']['event_time']) : '' ?></td>
                            <td><?= ValueTypeEnum::getValue($value['newValue']['type']) ?></td>
                            <td><?= $value['newValue']['value'] ?></td>
                            <td>
                                <?= Html::a('添加数据', ['/monitor-project/point-value/ajax-edit', 'pid' => $value['id']], [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ]) ?>
                                <?= Html::a('编辑', ['/monitor-project/point/ajax-edit', 'id' => $value['id']], [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ]) ?>
                                <?= Html::delete(['/monitor-project/point/delete', 'id' => $value['id']], '删除', ['class' => 'red']) ?>

                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-area-chart blue" style="font-size: 8px"></i>
                <h3 class="box-title">房屋地址</h3>
            </div>
            <div class="box-body">
                <div id="allmap"></div>
            </div>
        </div>
    </div>
</div>


<script type="text/javascript">
    var devices = <?= json_encode($devices) ?>;
    var map = new BMapGL.Map("allmap");
    map.centerAndZoom(new BMapGL.Point(<?= $model->lng ?>, <?= $model->lat ?>), 19);
    map.enableScrollWheelZoom(true);
    map.setHeading(64.5);
    map.setTilt(73);
    var point = new BMapGL.Point(<?= $model->lng ?>, <?= $model->lat ?>);
    var marker1 = new BMapGL.Marker(point);
    // 在地图上添加点标记
    map.addOverlay(marker1);
    // 地址信息
    var opts = {
        width: 200, // 信息窗口宽度
        height: 100, // 信息窗口高度
        title: "<?= $model['title'] ?>", // 信息窗口标题
        message: "<?= WarnEnum::getValue($model['warn']['warn']) ?>"
    }
    var infoWindow = new BMapGL.InfoWindow("地址：<?= $model['address'] ?>", opts); // 创建信息窗口对象 
    marker1.addEventListener("click", function() {
        map.openInfoWindow(infoWindow, point); //开启信息窗口
    });
    // var pt = new BMapGL.Point(<?= $model->lng ?>, <?= $model->lat ?>);
    // // console.log(citys[i], pt)

    devices.forEach(element => {
        var point = new BMapGL.Point(element.lng, element.lat);
        var marker = new BMapGL.Marker3D(point, element.height, {
            size: 20,
            shape: 'BMAP_SHAPE_CIRCLE',
            fillColor: '#ff0000',
            fillOpacity: 0.6
        });
        map.addOverlay(marker);
        // 窗口信息
        var opts = {
            width: 200, // 信息窗口宽度
            height: 100, // 信息窗口高度
            title: element.device.number, // 信息窗口标题
            message: element.device.device_name
        }
        var infoWindow = new BMapGL.InfoWindow("位置：" + element.location, opts); // 创建信息窗口对象 
        marker.addEventListener("click", function() {
            map.openInfoWindow(infoWindow, point); //开启信息窗口
        });
    });

    var navi3DCtrl = new BMapGL.NavigationControl3D(); // 添加3D控件
    map.addControl(navi3DCtrl);
</script>
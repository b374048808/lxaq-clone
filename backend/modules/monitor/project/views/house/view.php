<?php

use common\enums\BellEnum;
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
use common\enums\StatusEnum;
use common\enums\WarnEnum;

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
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">概况</a></li>
                <li><a href="<?= Url::to(['/monitor-project/point/index', 'pid' => $model['id']], $schema = true) ?>">监测点</a></li>
                <li><a href="<?= Url::to(['monitor', 'id' => $model['id']], $schema = true) ?>">实时监测</a></li>
                <li><a href="<?= Url::to(['data-chart', 'id' => $model['id']], $schema = true) ?>">数据曲线</a></li>
                <li><a href="<?= Url::to(['report', 'id' => $model['id']], $schema = true) ?>">报告</a></li>
                <li class="pull-right">
                    <?= Html::edit(['edit', 'id' => $model['id']], '<i class="fa fa-edit"></i>编辑'); ?>
                </li>
            </ul>
            <div class="box-body table-responsive">
                <div class="col-md-8 col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <td style="max-width: 160px;height:144px;text-align: center;" colspan="2" rowspan="5">
                                <?php if (isset($model->cover)) : ?>
                                    <?= ImageHelper::fancyBox($model->cover, 'auto', '100%'); ?>
                                <?php endif; ?>
                            </td>

                        </tr>
                        <tr>
                            <td>户主</td>
                            <td><?= $model->title ?></td>
                        </tr>
                        <tr>
                            <td>联系方式</td>
                            <td><?= $model->mobile ?></td>
                        </tr>
                        <tr>
                            <td>年代</td>
                            <td><?= $model->year ?></td>
                        </tr>
                        <tr>
                            <td>面积</td>
                            <td><?= $model->area ?></td>
                        </tr>
                        <tr>
                            <td>性质</td>
                            <td><?= StructEnum::natureMap()[$model->nature] ?></td>
                            <td>层数</td>
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
                    </table>
                </div>
                <div class="col-xs-12 col-md-4">
                    <table class="table">
                        <tr>
                            <td>长×宽×高</td>
                            <td><?= $model->length . '×' . $model->width . '×' . $model->height  ?></td>
                        </tr>
                        <tr>
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
                        </tr>
                        <tr>
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
                        </tr>
                        <tr>
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
                        </tr>
                        <tr>
                            <td>平顶位移点位示意图</td>
                            <td>
                                <?php foreach ($model->move_cover ?: [] as $key => $value) : ?>
                                    <?= ImageHelper::fancyBox($value); ?>
                                <?php endforeach; ?>

                            </td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <i class="fa fa-warning blue" style="font-size: 8px"></i>
                            <h3 class="box-title">报警触发器</h3>
                            <div class="box-tools">
                                <?= Html::create(['/monitor-project/rule-item/ajax-edit', 'pid' => $model['id']], '添加触发器', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]) ?>
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
                </div>
                <div class="col-md-6 col-xs-12">
                    <div class="box">
                        <div class="box-header">
                            <i class="fa fa-bell blue" style="font-size: 8px"></i>
                            <h3 class="box-title">提醒列表</h3>
                            <div class="box-tools">
                                <?= Html::create(['/monitor-project/bell/ajax-edit', 'pid' => $model['id']], '添加触发器', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]) ?>
                            </div>
                        </div>
                        <div class="box-body table-responsive">
                            <?= GridView::widget([
                                'dataProvider' => $bellProvider,
                                //重新定义分页样式
                                'tableOptions' => ['class' => 'table table-hover'],
                                'columns' => [
                                    [
                                        'class' => 'yii\grid\SerialColumn',
                                    ],
                                    [
                                        'attribute' => 'user.username',
                                    ],
                                    [
                                        'attribute' => 'type',
                                        'value' => function ($que) {
                                            return MonitorBellEnum::getValue($que['type']);
                                        },
                                        'format' => 'html',
                                    ],
                                    [
                                        'attribute' => 'event_time',
                                        'format' => ['date', 'php:Y-m-d'], //不显示搜索框
                                    ],
                                    
                                    [
                                        'attribute' => 'state',
                                        'value' => function ($que) {
                                            return BellStateEnum::getValue($que['state']);
                                        },
                                    ],
                                    'description',
                                    [
                                        'header' => "操作",
                                        'class' => 'yii\grid\ActionColumn',
                                        'template' => '{edit} {status} {destroy}',
                                        'buttons' => [
                                            'edit' => function ($url, $model, $key) {
                                                return Html::edit(['/monitor-project/bell/ajax-edit', 'id' => $model->id], '编辑', [
                                                    'class' => 'blue',
                                                    'data-toggle' => 'modal',
                                                    'data-target' => '#ajaxModalLg',
                                                ]);
                                            },
                                            'destroy' => function ($url, $model, $key) {
                                                return Html::delete(['/monitor-project/bell/destroy', 'id' => $model->id], '删除', ['class' => 'red']);
                                            },
                                        ],
                                    ],
                                ],
                            ]); ?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <?php foreach ($points as $key => $value) : ?>
                    <div class="col-md-6 col-xs-12 col-sm-12">
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
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="box box-solid">
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
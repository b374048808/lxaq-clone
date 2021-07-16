<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 11:36:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 17:18:37
 * @Description: 
 */
use yii\grid\GridView;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\helpers\Url;
use common\helpers\Html;

$this->title = $model['iccid'];
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => Url::to(['/sim-list/card/index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <div class="box-header">
                <i class="fa fa-newspaper-o blue" style="font-size: 8px"></i>
                <h3 class="box-title"><?= $this->title ?></h3>
                <div class="box-tools">
                    <?= Html::linkButton(['/monitor-project/huawei-device/index', 'pid' => $model['id']], '<i class="icon ion-link"></i> 安装设备', [
                        'class' => 'btn btn-info btn-xs'
                    ]); ?>
                    <?= Html::linkButton(['/monitor-log/point-warn/index', 'pid' => $model['id']], '报警日志', [
                        'class' => 'btn btn-info btn-xs',
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>

            <div class="box-body table-responsive">
                <div class="col-xs-12">
                    <table class="table table-hover">
                        <tr>
                            <td>卡号</td>
                            <td><?= $model['iccid'] ?></td>
                            <td>状态</td>
                            <td><?= StatusEnum::getValue($model['status']) ?></td>
                        </tr>
                        <tr>

                        </tr>
                    </table>
                </div>
            </div>
            <div class="box-header">
                <i class="fa fa-history blue" style="font-size: 8px"></i>
                <h3 class="box-title">近期续期记录</h3>
                <div class="box-tools">
                    <?= Html::linkButton(['/sim-log/renewal/index', 'pid' => $model['id']], '查看记录', [
                        'class' => 'btn btn-info btn-xs',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid-ajax'],
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    // 'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        'user.username',
                        'card.iccid',
                        'day',
                        [
                            'attribute' => 'expiration_time',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d'],
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 11:36:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 14:10:52
 * @Description: 
 */

use common\enums\NewsEnum;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\helpers\Url;
use common\helpers\Html;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use common\helpers\ImageHelper;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => Url::to(['/monitor-project/item/index'])];
$this->params['breadcrumbs'][] = ['label' => $model->house->title, 'url' => Url::to(['/monitor-project/house/view', 'id' => $model['pid']], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];
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
    <div class="col-xs-10">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title"><?= $this->title ?></h3>
                <a href="<?= Url::to(['ajax-edit', 'id' => $model['id']], $schema = true) ?>" data-toggle='modal' , data-target='#ajaxModalLg' ,>
                    <i class="fa fa-edit blue" style="font-size: 12px"></i>
                </a>

                <div class="box-tools">
                    <?= Html::linkButton(['/monitor-project/huawei-device/index', 'pid' => $model['id']], '<i class="icon ion-link"></i> 安装设备', [
                        'class' => 'btn btn-info btn-xs'
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>点位类型</td>
                        <td><?= PointEnum::getMap()[$model['type']] ?></td>
                        <td>初始数据</td>
                        <td><?= $model['initial_value'] ?></td>
                        <td>状态</td>
                        <td><?= StatusEnum::getValue($model['status']) ?></td>
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
    </div>
    <div class="col-xs-2">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-circle red" style="font-size: 8px"></i>
                <h3 class="box-title">报警</h3>
                <div class="box-tools">
                    <?= Html::linkButton(['/monitor-project/warn/ajax-list', 'pid' => $model['id']], '报警历史记录', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive" style="text-align: center;">
                <?php if ($model->warnState) : ?>
                    <?= Html::linkButton(['/monitor-project/warn/view', 'id' => $model->warnState['id']], '<i class="fa fa-bell" style="font-size: 24px"></i> ' . WarnEnum::getValue($model->warnState['warn']), [
                        'class' => 'btn btn-danger btn-xs',
                        'style' => 'font-size:24px;line-height:54px;width:80%',
                    ]) ?>
                <?php else : ?>
                    <?= Html::linkButton(['/monitor-project/warn/ajax-edit', 'pid' => $model['id']], '<i class="fa fa-bell" style="font-size: 24px"></i> 发起报警', [
                        'class' => 'btn btn-warning btn-xs',
                        'style' => 'font-size:24px;line-height:54px;width:80%',
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                <?php endif; ?>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="<?= $valueType == ValueTypeEnum::AUTOMATIC ? 'active' : '' ?>">
                    <a href="<?= $valueType == ValueTypeEnum::AUTOMATIC ? '#' : Url::to(['view', 'id' => $model['id'], 'valueType' => ValueTypeEnum::AUTOMATIC]) ?>">
                        <?= ValueTypeEnum::getValue(ValueTypeEnum::AUTOMATIC) ?>
                    </a>
                </li>
                <li class="<?= $valueType == ValueTypeEnum::MANUAL ? 'active' : '' ?>">
                    <a href="<?= $valueType == ValueTypeEnum::MANUAL ? '#' : Url::to(['view', 'id' => $model['id'], 'valueType' => ValueTypeEnum::MANUAL]) ?>">
                        <?= ValueTypeEnum::getValue(ValueTypeEnum::MANUAL) ?>
                    </a>
                </li>
                <li class="pull-right">
                    <?= Html::linkButton(['/monitor-project/point-value/index', 'pid' => $model['id']], '<i class="icon ion-navicon"></i> 历史数据', [
                        'class' => 'btn btn-info btn-xs'
                    ]); ?>
                </li>
            </ul>
            <div class="box-body table-responsive">
                <table class="table">
                    <tr>
                        <td>最新数据</td>
                        <td><?= $model['newValue']['value'] ?></td>
                        <td>时间</td>
                        <td><?= $model['newValue']['event_time'] ? date('Y-m-d H:i:s', $model['newValue']['event_time']) : '' ?></td>
                        <td>报警等级</td>
                        <td><?= WarnEnum::getValue($model['newValue']['warn']) ?></td>
                        <td>操作</td>
                        <td><?= Html::a('查看', ['/monitor-project/point-value/view', 'id' => $model['newValue']['id']], $options = [
                                'data-toggle' => 'modal',
                                'data-target' => '#ajaxModalLg',
                            ]) ?></td>
                    </tr>
                    <tr>
                        <td colspan="8">
                            <?= \common\widgets\echarts\Echarts::widget([
                                'config' => [
                                    'server' => Url::to(['value-between-chart', 'id' => $model['id'], 'valueType' => Yii::$app->request->get('valueType', ValueTypeEnum::AUTOMATIC)]),
                                    'height' => '400px'
                                ]
                            ]) ?>
                        </td>
                    </tr>
                </table>
            </div>

        </div>
    </div>
</div>


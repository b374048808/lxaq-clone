<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 15:18:06
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-25 13:55:12
 * @Description: 
 */

use common\enums\device\SwitchEnum;
use common\enums\WarnEnum;
use common\helpers\BaseHtml;
use common\helpers\Html;
use yii\widgets\LinkPager;
use common\models\monitor\project\House;

$this->title = '设备监测列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <div class="box-body table-responsive">
                <div class="row" style="margin-top: 25px;">
                    <div class="col-xs-8">
                        <div class="col-xs-4">
                            <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">房屋数量</p>
                            <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $houseCount ?></p>
                        </div>
                        <div class="col-xs-4">
                            <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">点位数量</p>
                            <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $pointCount ?></p>
                        </div>
                        <div class="col-xs-4">
                            <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">设备数量</p>
                            <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $deviceCount ?></p>
                        </div>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>房屋列表</th>
                            <th>动态点位</th>
                            <th>点位状态</th>
                            <th>报警开关</th>
                            <th>最新数据</th>
                            <th>更新时间</th>
                            <th>关联设备编号</th>
                            <th>设备开关</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1;
                        foreach ($models as $key => $model) {
                        ?>
                            <tr>
                                <td rowspan="<?= count($model) + 1 ?>">
                                    <?= $i++  ?></td>
                                <td rowspan="<?= count($model) + 1 ?>">
                                    <?= Html::a(House::getTitle($key), ['/monitor-project/house/view', 'id' => $key], [
                                        'class' => 'openContab'
                                    ]) ?>
                                </td>
                            </tr>
                            <?php foreach ($model as $value) { ?>
                                <tr>
                                    <td><?= Html::a($value['point']['title'], ['/monitor-project/point/view', 'id' => $value['point_id']], [
                                            'class' => 'openContab'
                                        ]) ?> </td>
                                    <td><?= WarnEnum::$spanlistExplain[Yii::$app->services->pointWarn->getPointWarn($value['point_id'])] ?></td>
                                    <td><?= SwitchEnum::getValue($value['point']['warn_switch']) ?></td>
                                    <td><?= $value['value']['value'] ?></td>
                                    <td><?= $value['value']['event_time'] ? date('Y-m-d H:i:s', $value['value']['event_time']) : '' ?></td>
                                    <td><?= Html::a($value['device']['number'], ['/console-huawei/device/view', 'id' => $value['device_id']], [
                                            'class' => 'openContab'
                                        ]) ?></td>
                                    <td><?= SwitchEnum::getValue($value['device']['switch']) ?></td>
                                    <td>
                                        <?= BaseHtml::delete(['delete', 'id' => $value['id']]) ?>
                                    </td>
                                </tr>
                            <?php } ?>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <?= LinkPager::widget([
                    'pagination' => $pages
                ]); ?>
            </div>
        </div>
    </div>
</div>
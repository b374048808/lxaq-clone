<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 14:27:08
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-07 14:37:00
 * @Description: 
 */

use common\enums\PointEnum;
use common\enums\JudgeEnum;
use common\enums\WarnEnum;
use common\helpers\Html;
use yii\helpers\Url;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <table class="table table-striped table-bordered detail-view">
        <tbody>
            <tr>
                <td>触发器判断</td>
                <td><?= JudgeEnum::getValue($model->item->judge).$model->item->value ?></td>
            </tr>
            <tr>
                <td>触发器报警等级</td>
                <td><?= WarnEnum::$spanlistExplain[$model->item->warn] ?></td>
            </tr>
            <tr>
                <td>备注</td>
                <td><?= $model->item->description ?></td>
            </tr>
            <tr>
                <td>监测建筑</td>
                <td><?= Html::a($model->house->title, Url::to(['/monitor-project/house/view', 'id' => $model->house->id]), $options = ['class' => 'openContab']) ?></td>
            </tr>
            <tr>
                <td>监测点位</td>
                <td><?= Html::a($model->point->title, Url::to(['/monitor-project/point/view', 'id' => $model->point->id]), $options = ['class' => 'openContab']) ?></td>
            </tr>
            <tr>
                <td>点位类型</td>
                <td><?= PointEnum::getValue($model->point->type) ?></td>
            </tr>
            <tr>
                <td>数值</td>
                <td><?= $model->value ?></td>
            </tr>
            <tr>
                <td>时间</td>
                <td><?= date('Y-m-d H:i:s', $model->created_at) ?></td>
            </tr>
        </tbody>
    </table>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
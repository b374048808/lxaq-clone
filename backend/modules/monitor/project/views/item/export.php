<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-08 09:32:48
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-14 09:58:31
 * @Description: 
 */

use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;
use yii\helpers\Url;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<?php $form = ActiveForm::begin([
    'action' => Url::to(['export']),
    'method' => 'post'
]); ?>
<div class="modal-body">
    <div class="form-group">
        <div class="input-group drp-container">
            <?= DateRangePicker::widget([
                'name' => 'formModel',
                'value' => $from_date . '-' . $to_date,
                'readonly' => 'readonly',
                'useWithAddon' => true,
                'convertFormat' => true,
                'startAttribute' => 'from_date',
                'endAttribute' => 'to_date',
                'startInputOptions' => ['value' => $from_date ? date('Y-m-d', $from_date) : date('Y-m-d', strtotime('-1 month'))],
                'endInputOptions' => ['value' => $to_date ? date('Y-m-d', $to_date) : date('Y-m-d')],
                'pluginOptions' => [
                    'locale' => ['format' => 'Y-m-d'],
                ]
            ]) . $addon; ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
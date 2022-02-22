<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 13:06:33
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-02 10:49:23
 * @Description: 
 */

use common\enums\AuditEnum;
use common\enums\VerifyEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    // 'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-audit','id' => $model['pid']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">审核状态修改</h4>
</div>
<div class="modal-body">
<?= $form->field($model, 'verify')->radioList(VerifyEnum::getMap()) ?>
<?= $form->field($model, 'description')->textarea() ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
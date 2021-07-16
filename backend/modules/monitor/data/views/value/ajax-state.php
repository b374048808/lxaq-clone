<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 13:06:33
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-07 14:51:33
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\ValueStateEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-state','id' => $model['id'], 'type' => $type]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
<?= $form->field($model, 'state')->radioList(ValueStateEnum::getStateMap()) ?>
<?= $form->field($model, 'remark')->textarea() ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
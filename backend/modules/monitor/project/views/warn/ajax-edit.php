<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 15:26:55
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 09:24:43
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use common\enums\WarnStateEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'pid' => $model['pid'], 'id' => $model['id']]),
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
    <?= $form->field($model, 'warn')->dropDownList(WarnEnum::getMap()) ?>
    <?= $form->field($model, 'sort')->textInput() ?>
    <?php if(!$model->isNewRecord): ?>
        <?= $form->field($model, 'state')->radioList(WarnStateEnum::getMap()) ?>
    <?php endif; ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
    <?= $form->field($model, 'description')->textarea() ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
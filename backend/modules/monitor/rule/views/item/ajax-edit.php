<?php

use common\enums\PointEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WarnEnum;
use common\enums\JudgeEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    
    <?= $form->field($model, 'type')->dropDownList(PointEnum::getMap()) ?>
    <?= $form->field($model, 'warn')->dropDownList(WarnEnum::getMap()) ?>
    <?= $form->field($model, 'judge')->dropDownList(JudgeEnum::getMap()) ?>
    <?= $form->field($model, 'value')->textInput() ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
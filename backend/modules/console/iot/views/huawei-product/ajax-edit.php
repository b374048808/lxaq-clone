<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\IotTypeEnum;
use common\widgets\webuploader\Files;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
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
    <?= $form->field($model, 'name')->textInput() ?>
    <?= $form->field($model, 'cover')->widget(Files::class, [
        'config' => [
            // 可设置自己的上传地址, 不设置则默认地址
            // 'server' => '',
            'pick' => [
                'multiple' => false,
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'product_key')->textInput() ?>
    <?= $form->field($model, 'type')->textInput() ?>
    <?= $form->field($model, 'sort')->textInput(); ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
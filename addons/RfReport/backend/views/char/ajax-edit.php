<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use addons\RfReport\common\models\Char;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit','id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput(); ?>
        <?= $form->field($model, 'type')->dropDownList(Char::$typeMap); ?>
        <?= $form->field($model, 'char')->textInput(); ?>
        <?= $form->field($model, 'default')->textInput(); ?>
        <?= $form->field($model, 'description')->textarea(); ?>
        <?= $form->field($model, 'sort')->textInput(); ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
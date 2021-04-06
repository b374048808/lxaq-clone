<?php

use yii\widgets\ActiveForm;
use common\models\iot\Product;
use common\enums\StatusEnum;
use common\helpers\Url;

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
    <p>在第三方云平台没有设备，直接创建设备！</p>
    <?= $form->field($model, 'pid')->dropDownList(Product::getMapList()) ?>
    <?= $form->field($model, 'device_name')->textInput() ?>
    <?= $form->field($model, 'device_id')->textInput() ?>
    <?= $form->field($model, 'iccid')->textInput() ?>
    <?= $form->field($model, 'expiration_time')->widget(kartik\date\DatePicker::class, [
        'language' => 'zh-CN',
        'layout' => '{picker}{input}',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true, //今日高亮
            'autoclose' => true, //选择后自动关闭
            'todayBtn' => true, //今日按钮显示
        ],
        'options' => [
            'class' => 'form-control no_bor',
        ]
    ]); ?>
    <?= $form->field($model, 'sort')->textInput(); ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
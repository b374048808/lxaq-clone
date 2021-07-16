<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use addons\RfMonitor\common\enums\NewsEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-4 text-right'>{label}</div><div class='col-sm-8'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">基本信息(单位米)</h4>
</div>
<div class="modal-body">
    <?= $form->field($model, 'title')->textInput(); ?>
    <?= $form->field($model, 'pid')->hiddenInput()->label(false); ?>
    <?= $form->field($model, 'level_first')->textInput(); ?>
    <?= $form->field($model, 'level_second')->textInput(); ?>    
    <?= $form->field($model, 'vertical_first')->textInput(); ?>
    <?= $form->field($model, 'vertical_second')->textInput(); ?>
    <?= $form->field($model, 'level')->textInput(); ?>
    <?= $form->field($model, 'vertical')->textInput(); ?>
    <?= $form->field($model, 'value')->textInput()->hint('‰'); ?>
    <?= $form->field($model, 'news')->dropDownList(array_merge([0 => '未选择'], NewsEnum::getMap())); ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
<script>
    var levelValue = $("#angle-level").val();
    var verticalValue = $("#angle-vertical").val();

    function levelChange() {
        var first = $("#angle-level_first").val();
        var second = $("#angle-level_second").val();
        if (first === '' || second === '' || first === null || second === null) {
            return false;
        }
        if (!isNaN(first) || !isNaN(second)) {
            levelValue = (second - first).toFixed(3);
            $("#angle-level").val(levelValue);
            valueChange()
            return true;
        } else {
            return false;
        }


    }

    function verticalChange() {
        var first = $("#angle-vertical_first").val();
        var second = $("#angle-vertical_second").val();
        if (first === '' || second === '' || first === null || second === null) {
            return false;
        }
        if (!isNaN(first) || !isNaN(second)) {
            verticalValue =  (second - first).toFixed(3);
            $("#angle-vertical").val(verticalValue);
            valueChange()
            return true;
        } else {
            return false;
        }
    }

    function valueChange() {
        if (verticalValue === '' || verticalValue == null || levelValue === '' | levelValue == null) {
            return false;
        }
        if (!isNaN(verticalValue) || !isNaN(levelValue)) {
            console.log(levelValue / verticalValue);
            $("#angle-value").val((levelValue / verticalValue * 1000).toFixed(3));
            return true;
        } else {
            return false;
        }

    }
    $("#angle-level_first").on('change', function() {
        levelChange()
    })
    $("#angle-level_second").on('change', function() {
        levelChange()
    })
    $("#angle-level").on('change', function() {
        levelValue = $(this).val();
        valueChange()
    })
    $("#angle-vertical_first").on('change', function() {
        verticalChange()
    })
    $("#angle-vertical_second").on('change', function() {
        verticalChange()
    })
    $("#angle-vertical").on('change', function() {
        verticalValue = $(this).val();
        valueChange()
    })
</script>
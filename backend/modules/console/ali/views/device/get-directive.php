<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['/console-huawei/device/get-directive', 'id' => $id, 'directive_id' => $directive_id]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    
    <?= $form->field($model, 'value')->textInput()->hint('单位：秒') ?>
    <div class="form-group has-success">
        <div class="col-sm-2 text-right"><label class="control-label">快速选择</label></div>
        <div class="col-sm-10">
            <?= Html::tag('span', '<a class="btn btn-white" style="width:80%" href="javascript:setValue(60)">1分钟</a>', ['class' => 'input-group-btn']) ?>
            <?= Html::tag('span', '<a class="btn btn-white" style="width:80%" href="javascript:setValue(300)">5分钟</a>', ['class' => 'input-group-btn']) ?>
            <?= Html::tag('span', '<a class="btn btn-white" style="width:80%" href="javascript:setValue(3600)">60分钟</a>', ['class' => 'input-group-btn']) ?>
            <?= Html::tag('span', '<a class="btn btn-white" style="width:80%" href="javascript:setValue(86400)">一天</a>', ['class' => 'input-group-btn']) ?>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
<script>
    function setValue(value) {
        document.getElementById("directiveform-value").value = value;
    }
</script>
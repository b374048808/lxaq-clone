<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-23 10:51:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-23 15:50:46
 * @Description: 
 */

use common\enums\CardEnum;
use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['renewal', 'id' => $id]),
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
    <?= $form->field($model, 'number')->textInput() ?>
    <?= $form->field($model, 'unit')->dropDownList(['天','月','年']) ?>
    <?= $form->field($model, 'description')->textarea() ?>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
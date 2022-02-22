<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-12 14:03:59
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-12 14:17:18
 * @Description: 
 */

use common\enums\monitor\ItemStepsEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use kartik\select2\Select2;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    
<?= $form->field($model, 'step_id')->dropDownList(ItemStepsEnum::getMap()) ?>
    <?= $form->field($model, 'member_id')->widget(Select2::class, [
        'data' => $members,
        'options' => ['placeholder' => '请选择'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
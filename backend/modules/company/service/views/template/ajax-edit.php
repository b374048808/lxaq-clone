<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-24 14:25:35
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-08-25 11:01:23
 * @Description: 
 */

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
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'push_id')->dropDownList($roles,['prompt' => '选择用户']); ?>
    <?= $form->field($model, 'description')->textarea(); ?>
    <?= $form->field($model, 'sort')->textInput()->hint('升序'); ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
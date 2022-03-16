<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-16 15:28:30
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-23 14:22:00
 * @Description: 
 */

use common\enums\device\SwitchEnum;
use yii\widgets\ActiveForm;
use common\models\console\iot\huawei\Product;
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
    <?= $form->field($model, 'number')->textInput() ?>
    <?= $form->field($model, 'device_id')->textInput() ?>
    <?= $form->field($model, 'card')->textInput() ?>
    <?= $form->field($model, 'sort')->textInput(); ?>
    <?= $form->field($model, 'switch')->radioList(SwitchEnum::getMap()) ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
</div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
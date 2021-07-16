<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-08 14:15:39
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 10:23:46
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

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
    <?= $form->field($model, 'start_time')->widget(kartik\datetime\DateTimePicker::class, [
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $model['start_time']),
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true, // 今日高亮
            'autoclose' => true, // 选择后自动关闭
            'todayBtn' => true, // 今日按钮显示
        ]
    ]); ?>
    <?= $form->field($model, 'end_time')->widget(kartik\datetime\DateTimePicker::class, [
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->isNewRecord ? date('Y-m-d H:i:s',strtotime('+1 month')) : date('Y-m-d H:i:s', $model['end_time']),
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true, // 今日高亮
            'autoclose' => true, // 选择后自动关闭
            'todayBtn' => true, // 今日按钮显示
        ]
    ]); ?>
    <?= $form->field($model, 'start_value')->textInput() ?>
    <?= $form->field($model, 'end_value')->textInput() ?>
    <?= $form->field($model, 'interval')->textInput()->hint('小时') ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'sort')->textInput() ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
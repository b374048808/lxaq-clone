<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-01 10:45:08
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-05 09:42:25
 * @Description: 
 */

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
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <?= $form->field($model, 'pid')->widget(Select2::class, [
        'data' => $items,
        'options' => ['placeholder' => '请选择'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'manager')->widget(Select2::class, [
        'data' => $roles,
        'options' => ['placeholder' => '请选择'],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]); ?>
    <?= $form->field($model, 'contact')->textInput(); ?>
    <?= $form->field($model, 'mobile')->textInput(); ?>
    <?= $form->field($model, 'description')->textarea(); ?>

    <?= $form->field($model, 'start_time')->widget(kartik\date\DatePicker::class, [
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
            'value' => $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', $model->start_time),
        ]
    ]); ?>
    <?= $form->field($model, 'end_time')->widget(kartik\date\DatePicker::class, [
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
            'value' => $model->isNewRecord ? date('Y-m-d', strtotime('+1 month')) : date('Y-m-d', $model->end_time),
        ]
    ]); ?>
    
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
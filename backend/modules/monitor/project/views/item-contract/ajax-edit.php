<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-11 11:38:14
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-02 14:10:23
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\widgets\webuploader\Files;
use kartik\select2\Select2;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id'], 'pid' => $model['pid']]),
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
    <?= $form->field($model, 'manager')->widget(Select2::class, [
        'data' => $members,
        'options' => ['placeholder' => '请选择'],
        'pluginOptions' => [
            'allowClear' => false
        ],
    ]); ?>
    <?= $form->field($model, 'money')->textInput() ?>
    <?= $form->field($model, 'file')->widget(Files::class, [
        'config' => [
            // 可设置自己的上传地址, 不设置则默认地址
            // 'server' => '',
            'pick' => [
                'multiple' => true,
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'event_time')->widget(kartik\date\DatePicker::class, [
        'language' => 'zh-CN',
        'layout' => '{picker}{input}',
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd',
            'todayHighlight' => true, // 今日高亮
            'autoclose' => true, // 选择后自动关闭
            'todayBtn' => true, // 今日按钮显示
        ],
        'options' => [
            'class' => 'form-control no_bor',
        ]
    ]); ?>
    <?= $form->field($model, 'description')->textarea() ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
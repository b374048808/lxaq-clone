<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-16 10:00:09
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 10:17:49
 * @Description: 
 */

use common\enums\ValueTypeEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['value-rand','id' => $model->pid]),
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
        <?= $form->field($model, 'min_value')->textInput() ?>
        <?= $form->field($model, 'max_value')->textInput()->hint('例如:0.0001,最小4位小数') ?>
        <?= $form->field($model, 'rand_time')->textInput()->hint('单位:小时(整数)') ?>
        <?= $form->field($model, 'type')->dropDownList(ValueTypeEnum::getMap())?>
        <?= $form->field($model, 'start_time')->widget(kartik\datetime\DateTimePicker::class, [
            'language' => 'zh-CN',
            'options' => [
                'value' =>  date('Y-m-d H:i:s'),
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'todayHighlight' => true, // 今日高亮
                'autoclose' => true, // 选择后自动关闭
                'todayBtn' => true, // 今日按钮显示
            ]
        ]);?>
        <?= $form->field($model, 'end_time')->widget(kartik\datetime\DateTimePicker::class, [
            'language' => 'zh-CN',
            'options' => [
                'value' =>date('Y-m-d H:i:s'),
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'todayHighlight' => true, // 今日高亮
                'autoclose' => true, // 选择后自动关闭
                'todayBtn' => true, // 今日按钮显示
            ]
        ]);?>
        
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
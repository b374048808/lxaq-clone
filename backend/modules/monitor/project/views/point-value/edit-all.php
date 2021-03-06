<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 15:26:55
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-13 10:08:48
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['edit-all','keys' => $keys]),
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
    <?= $form->field($model, 'value')->textInput() ?>
    <?= $form->field($model, 'event_time')->widget(kartik\datetime\DateTimePicker::class, [
        'language' => 'zh-CN',
        'options' => [
            'value' => date('Y-m-d H:i:s'),
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true, // 今日高亮
            'autoclose' => true, // 选择后自动关闭
            'todayBtn' => true, // 今日按钮显示
        ]
    ]); ?>
    <?= $form->field($model, 'warn')->dropDownList(WarnEnum::getMap()) ?>
    <?= $form->field($model, 'type')->radioList(ValueTypeEnum::getMap()) ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
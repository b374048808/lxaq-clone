<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 10:33:56
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-03-10 15:10:27
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\widgets\webuploader\Files;
use common\enums\AxisEnum;
use common\enums\NewsEnum;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'validationUrl' => Url::to(['ajax-device', 'point_id' => $model->point_id, 'id' => $model['id']]),
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
    <?= $form->field($model, 'point_id')->hiddenInput()->label(false) ?>
    <?= $form->field($model, 'device_id')->widget(kartik\select2\Select2::class, [
        'data' => $devices,
        'options' => ['placeholder' => '请选择'],
        'pluginOptions' => [
            'allowClear' => true
        ],
    ]); ?>
    <?= $form->field($model, 'install_time')->widget(kartik\date\DatePicker::class, [
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
            'value' => $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', $model->install_time),
        ]
    ]); ?>
    <?= $form->field($model, 'covers')->widget(Files::class, [
        'config' => [
            // 可设置自己的上传地址, 不设置则默认地址
            // 'server' => '',
            'pick' => [
                'multiple' => true,
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'lnglat')->widget(\common\widgets\selectmap\Map::class, [
        'type' => 'amap', // amap高德;tencent:腾讯;baidu:百度
    ]); ?>
    <?= $form->field($model, 'height')->textInput() ?>
    <?= $form->field($model, 'axis')->dropDownList(AxisEnum::getMap()) ?>
    <?= $form->field($model, 'is_up')->dropDownList([1 => '正', -1 => '负']) ?>
    <?= $form->field($model, 'location')->textarea() ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
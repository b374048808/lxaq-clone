<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 10:33:56
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-21 16:51:16
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\widgets\webuploader\Files;
use common\enums\PointEnum;
use common\enums\StatusEnum;
use common\enums\NewsEnum;
use common\enums\WarnEnum;
use common\enums\device\SwitchEnum;
use common\enums\monitor\WarnTypeEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'pid' => $model->pid, 'id' => $model['id']]),
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
    <?= $form->field($model, 'type')->dropDownList(PointEnum::getMap()) ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'warn_type')->dropDownList(WarnTypeEnum::getMap()) ?>
    <?= $form->field($model, 'initial_value')->textInput() ?>
    <?= $form->field($model, 'covers')->widget(Files::class, [
        'config' => [
            // 可设置自己的上传地址, 不设置则默认地址
            // 'server' => '',
            'pick' => [
                'multiple' => true,
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'news')->dropDownList(NewsEnum::getMap()) ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'warn')->radioList(WarnEnum::getMap()) ?>
    <?= $form->field($model, 'sort')->textInput() ?>
    <?= $form->field($model, 'warn_switch')->radioList(SwitchEnum::getMap()) ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
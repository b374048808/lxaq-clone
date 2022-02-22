<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-01 10:45:08
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-01 11:41:57
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\widgets\webuploader\Files;

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
    <?= $form->field($model, 'images')->widget(Files::class, [
        'config' => [
            // 可设置自己的上传地址, 不设置则默认地址
            // 'server' => '',
            'pick' => [
                'multiple' => true,
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'files')->widget(Files::class, [
        'config' => [
            // 可设置自己的上传地址, 不设置则默认地址
            // 'server' => '',
            'pick' => [
                'multiple' => true,
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'description')->textarea(); ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
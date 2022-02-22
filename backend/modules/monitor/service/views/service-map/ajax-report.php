<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 15:26:55
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-02 16:00:26
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-report','id' => $id]),
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
    <?= $form->field($model, 'file_name')->textInput() ?>
    <?= $form->field($model, 'file')->widget(common\widgets\webuploader\Files::class, [
        'config' => [
            'pick' => [
                'multiple' => false,
            ],
            'formData' => [
                // 不配置则不生成缩略图
                'drive' => 'local', // 默认本地 支持 qiniu/oss/cos 上传
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'description')->textarea() ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
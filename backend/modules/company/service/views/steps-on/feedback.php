<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-08-24 14:25:35
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 17:36:10
 * @Description: 
 */

use common\enums\company\StepStatusEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\IotTypeEnum;
use common\widgets\webuploader\Files;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['feedback', 'id' => $model['id']]),
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
    <?= $form->field($model, 'feedback')->textarea(); ?>
    <?= $form->field($model, 'images')->widget(Files::class, [
        'config' => [
            // 可设置自己的上传地址, 不设置则默认地址
            // 'server' => '',
            'pick' => [
                'multiple' => true,
            ],
            'formData' => [
                // 不配置则不生成缩略图
                'thumb' => [
                    [
                        'width' => 100,
                        'height' => 100,
                    ],
                ],
                'drive' => 'local', // 默认本地 支持 qiniu/oss/cos 上传
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'file')->widget(Files::class, [
        'type' => 'files'
    ]); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
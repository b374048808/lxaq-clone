<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 15:26:55
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 09:11:35
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'pid' => $model['pid'], 'id' => $model['id']]),
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
    <?= $form->field($model, 'files')->widget(common\widgets\webuploader\Files::class, [
        'config' => [
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
                    [
                        'width' => 200,
                        'height' => 200,
                    ],
                ],
                'drive' => 'local', // 默认本地 支持 qiniu/oss/cos 上传
            ],
        ]
    ]); ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'sort')->textInput() ?>
    <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
    <?= $form->field($model, 'description')->textarea() ?>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
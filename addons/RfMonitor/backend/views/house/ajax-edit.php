<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit','id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput(); ?>
        <?= $form->field($model, 'hold')->textInput(); ?>
        <?= $form->field($model, 'covers')->widget('common\widgets\webuploader\Files', [
                    'type' => 'files',
                    'config' => [ // 配置同图片上传
                        // 'server' => \yii\helpers\Url::to(['file/files']), // 默认files 支持videos/voices/images方法验证
                        'pick' => [
                            'multiple' => false,
                        ]
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
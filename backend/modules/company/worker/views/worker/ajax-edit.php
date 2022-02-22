<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-12 10:44:53
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-17 16:58:02
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'class' => 'form-horizontal',
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

    <div class="modal-content">
        <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
            <h4 class="modal-title">基本信息</h4>
        </div>
        <div class="modal-body">
            <?= $form->field($model, 'username')->textInput([
                'readonly' => !empty($model->username)
            ])->hint('创建后不可修改') ?>
            <?= $form->field($model, 'password')->passwordInput() ?>
            <?= $form->field($model, 'role_id')->dropDownList($roles) ?>
            <?= $form->field($model, 'realname')->textInput() ?>
            <?= $form->field($model, 'mobile')->textInput() ?>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
            <button class="btn btn-primary" type="submit">保存</button>
        </div>
    </div>

<?php ActiveForm::end(); ?>
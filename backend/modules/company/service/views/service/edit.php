<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-12 10:44:53
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-09-09 15:31:37
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\enums\GenderEnum;
use common\enums\StatusEnum;
use kartik\select2\Select2;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '会员信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ],
            ]); ?>
            <div class="box-body">
                
                <?= $form->field($model, 'title')->textInput() ?>
                <?= $form->field($model, 'pid')->widget(Select2::class, [
                    'data' => $templates,
                    'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <?= $form->field($model, 'push_id')->dropDownList($roles, ['prompt' => '选择用户']); ?>
                <?= $form->field($model, 'sort')->textInput()->hint('降序'); ?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
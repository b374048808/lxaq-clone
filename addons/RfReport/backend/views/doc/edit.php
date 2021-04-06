<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\widgets\webuploader\Files;
use addons\RfReport\common\models\Char;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '模板管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-1 text-right'>{label}</div><div class='col-sm-11'>{input}{hint}{error}</div>",
                ],
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'pid')->hiddenInput()->label(false); ?>
                <?= $form->field($model, 'title')->textInput(); ?>
                <?php foreach ($chars as $key => $value) : ?>
                    <?php if ($value->char->type == Char::CHAR) : ?>
                        <div class="form-group field-model-title">
                            <div class="col-sm-1 text-right">
                                <label class="control-label" for="model-title"><?= $value->char->title ?></label>
                            </div>
                            <div class="col-sm-11">
                                <input type="text" id="model-title" class="form-control" name="Char[<?= $value->char->id ?>]" value="" aria-required="true" aria-invalid="false">
                                <div class="help-block">
                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($value->char->type == Char::IMG) : ?>
                        <div class="form-group field-model-title" style="margin-bottom: 10px;">
                            <div class="col-sm-1 text-right">
                                <label class="control-label" for="model-title"><?= $value->char->title ?></label>
                            </div>
                            <div class="col-sm-11" style="margin-bottom: 15px;">
                                <?= Files::widget(['name' => 'Char['.$value->char->id.']']) ?>
                            </div>
                        </div>
                        <div class="form-group field-model-title">
                            <div class="col-sm-1 text-right">
                                <label class="control-label" for="model-title">长</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" id="model-title" class="form-control" name="height[<?= $value->char->id ?>]" value="" aria-required="true" aria-invalid="false">
                                <div class="help-block">

                                </div>
                            </div>
                            <div class="col-sm-1 text-right">
                                <label class="control-label" for="model-title">宽</label>
                            </div>
                            <div class="col-sm-5">
                                <input type="text" id="model-title" class="form-control" name="width[<?= $value->char->id ?>]" value="" aria-required="true" aria-invalid="false">
                                <div class="help-block">

                                </div>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endforeach; ?>


                <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>

            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
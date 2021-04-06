<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;

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
                <?= $form->field($model, 'cate_id')->dropDownList($cates); ?>
                <?= $form->field($model, 'title')->textInput(); ?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
                <?= $form->field($model, 'file')->widget('common\widgets\webuploader\Files', [
                    'type' => 'files',
                    'config' => [ // 配置同图片上传
                        // 'server' => \yii\helpers\Url::to(['file/files']), // 默认files 支持videos/voices/images方法验证
                        'pick' => [
                            'multiple' => false,
                        ]
                    ]
                ]); ?>
            </div>
            <div class="row">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'chars')->checkboxList($chars); ?>
                    </div>
                </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
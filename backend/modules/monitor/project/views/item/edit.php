<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\widgets\webuploader\Files;


?>
<div class="row">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
        ]
    ]); ?>
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'title')->textInput() ?>
                <?= $form->field($model, 'cover')->widget(Files::class, [
                    'config' => [
                        // 可设置自己的上传地址, 不设置则默认地址
                        // 'server' => '',
                        'pick' => [
                            'multiple' => false,
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
                <?= $form->field($model, 'hold')->textInput() ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= $form->field($model, 'sort')->textInput() ?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
                <?= $form->field($model, 'description')->textarea() ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
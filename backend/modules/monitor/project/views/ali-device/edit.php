<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-29 11:19:09
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 15:00:21
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;
use common\widgets\webuploader\Files;
use common\helpers\Url;

?>
<div class="row">
    <?php $form = ActiveForm::begin([
        'fieldConfig' => [
            'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
        ],
        'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    ]); ?>
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'point_id')->hiddenInput()->label(false) ?>
                <?= $form->field($model, 'device_id')->widget(kartik\select2\Select2::class, [
                    'data' => $devices,
                    'options' => ['placeholder' => '请选择'],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
                <?= $form->field($model, 'install_time')->widget(kartik\date\DatePicker::class, [
                    'language' => 'zh-CN',
                    'layout' => '{picker}{input}',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true, // 今日高亮
                        'autoclose' => true, // 选择后自动关闭
                        'todayBtn' => true, // 今日按钮显示
                    ],
                    'options' => [
                        'class' => 'form-control no_bor',
                        'value' => $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', $model->install_time),
                    ]
                ]); ?>
                <?= $form->field($model, 'covers')->widget(Files::class, [
                    'config' => [
                        // 可设置自己的上传地址, 不设置则默认地址
                        // 'server' => '',
                        'pick' => [
                            'multiple' => true,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'lnglat')->widget(\common\widgets\selectmap\Map::class, [
                    'type' => 'amap', // amap高德;tencent:腾讯;baidu:百度
                ]); ?>
                <?= $form->field($model, 'height')->textInput() ?>
                <?= $form->field($model, 'location')->textarea() ?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
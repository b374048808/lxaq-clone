<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-29 11:19:09
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-22 11:28:26
 * @Description: 
 */

use common\enums\monitor\ItemStepsEnum;
use common\enums\monitor\ItemTypeEnum;
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
        'validationUrl' => Url::to(['edit', 'id' => $model['id']]),
    ]); ?>
    <div class="col-sm-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'title')->textInput() ?>                
                <?= $form->field($model, 'steps')->dropDownList(ItemStepsEnum::getMap()) ?>
                <?= $form->field($model, 'belonger')->textInput() ?>
                <?= $form->field($model, 'entrust')->textInput() ?>
                <?= $form->field($model, 'contact')->textInput() ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= \common\widgets\provinces\Provinces::widget([
                    'form' => $form,
                    'model' => $model,
                    'provincesName' => 'province_id', // 省字段名
                    'cityName' => 'city_id', // 市字段名
                    'areaName' => 'area_id', // 区字段名
                    // 'template' => 'short' //合并为一行显示
                ]); ?>
                <?= $form->field($model, 'address')->textInput() ?>
                <?= $form->field($model, 'type')->radioList(ItemTypeEnum::getMap()) ?>
                <?= $form->field($model, 'money')->textInput() ?>
                <?= $form->field($model, 'number')->textInput() ?>
                <?= $form->field($model, 'start_time')->widget(kartik\date\DatePicker::class, [
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
                        'value' => $model->isNewRecord ? date('Y-m-d') : date('Y-m-d', $model->start_time),
                    ]
                ]); ?>
                <?= $form->field($model, 'end_time')->widget(kartik\date\DatePicker::class, [
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
                        'value' => $model->isNewRecord ? date('Y-m-d', strtotime('+1 month')) : date('Y-m-d', $model->end_time),
                    ]
                ]); ?>
                <?= $form->field($model, 'file')->widget('common\widgets\webuploader\Files', [
     'type' => 'files',
     'config' => [ // 配置同图片上传
          // 'server' => '',
         'pick' => [
             'multiple' => true,
         ]
     ]
]);?>
                <?= $form->field($model, 'images')->widget(Files::class, [
                    'config' => [
                        // 可设置自己的上传地址, 不设置则默认地址
                        // 'server' => '',
                        'pick' => [
                            'multiple' => true,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'survey')->textarea() ?>
                <?= $form->field($model, 'demand')->textarea() ?>
                <?= $form->field($model, 'description')->textarea() ?>
                <?= $form->field($model, 'remark')->textarea() ?>
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
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2020-05-25 16:57:47
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-19 17:00:07
 * @Description: 
 */

use common\enums\PointEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-batch-edit', 'pid' => $model->pid]),
]);
?>
<?= Html::jsFile('@web/resources/js/rageframe.js'); ?>
<?= Html::jsFile('@web/resources/js/rageframe.widgets.js'); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <?= $form->field($model, 'points')->widget(unclead\multipleinput\MultipleInput::class, [
        'max' => 9,
        'columns' => [
            [
                'name'  => 'title',
                'title' => '点位名称',
                // 'enableError' => false,
                'options' => [
                    'class' => 'input-priority'
                ]
            ],
            [
                'name' => 'type',
                'title' => '类型',
                'type'  => 'dropDownList',
                'defaultValue' => PointEnum::ANGLE,
                'items' => PointEnum::getMap()
            ],
            [
                'name'  => 'location',
                'title' => '位置',
                // 'enableError' => false,
                'options' => [
                    'class' => 'input-priority'
                ]
            ]
        ]
    ]);
    ?>


</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 15:29:27
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-04-30 10:11:29
 * @Description: 
 */

use common\enums\NewsEnum;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use common\enums\ValueTypeEnum;
use common\enums\WarnEnum;

?>
<?= $form->field($model, 'news')->dropDownList(NewsEnum::getMap()) ?>
<?= $form->field($model, 'vertical')->textInput()->hint("单位m") ?>
    	<?= $form->field($model, 'level')->textInput()->hint("单位mm") ?>
        <?= $form->field($model, 'initial_value')->textInput() ?>
        <?= $form->field($model, 'value')->textInput() ?>
        <?= $form->field($model, 'event_time')->widget(kartik\datetime\DateTimePicker::class, [
            'language' => 'zh-CN',
            'options' => [
                'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $model->event_time),
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'todayHighlight' => true, // 今日高亮
                'autoclose' => true, // 选择后自动关闭
                'todayBtn' => true, // 今日按钮显示
            ]
        ]); ?>
        <?= $form->field($model, 'warn')->dropDownList(WarnEnum::getMap()) ?>
        <?= $form->field($model, 'type')->radioList(ValueTypeEnum::getMap()) ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()) ?>
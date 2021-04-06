<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\widgets\webuploader\Files;
use common\enums\PointEnum;
use common\enums\StatusEnum;

?>
<?= $form->field($model, 'vertical')->textInput()->hint("单位m") ?>
    	<?= $form->field($model, 'level')->textInput()->hint("单位mm") ?>
        <?= $form->field($model, 'value')->textInput() ?>
        <?= $form->field($model, 'event_time')->widget(kartik\datetime\DateTimePicker::class, [
            'language' => 'zh-CN',
            'options' => [
                'value' => $model->isNewRecord ? date('Y-m-d H:i:s'):date('Y-m-d H:i:s',$model->event_time),
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'todayHighlight' => true, // 今日高亮
                'autoclose' => true, // 选择后自动关闭
                'todayBtn' => true, // 今日按钮显示
            ]
        ]);?>
        <?= $form->field($model, 'type')->radioList([1=>'动态数据',2=>'人工数据']) ?>
<?php


use common\helpers\DebrisHelper;
use common\helpers\Html;
?>
<tr>
    <td>监测点位</td>
    <td><?= Html::encode($model['point']['title']) ?></td>
</tr>

<tr>
    <td style="min-width: 100px">原始数据</td>
    <td style="max-width: 700px">
        <?php Yii::$app->debris->p(DebrisHelper::htmlEncode(isset($model['deviceValue']) ? $model['deviceValue']['message'] : '')) ?>
    </td>
</tr>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-30 09:25:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-15 16:35:46
 * @Description: 
 */

use common\enums\NewsEnum;
use common\helpers\Html;

?>
<tr>
    <td>监测点位</td>
    <td><?= Html::encode($model['parent']['title']) ?></td>
</tr>
<tr>
    <td>数据</td>
    <td><?= Html::encode($model['value']) ?></td>
</tr>
<tr>
    <td>倾斜方向</td>
    <td><?= NewsEnum::getValue($model['news']) ?></td>
</tr>
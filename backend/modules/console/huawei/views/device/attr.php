<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-14 15:36:26
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-20 10:37:31
 * @Description: 
 */

use common\helpers\Url;

$this->title = '属性';
$this->params['breadcrumbs'][] = ['label' => '设备', 'url' => Url::to(['/console-huawei/device/index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['/console-huawei/device/view', 'id' => $id], $schema = true) ?>">概况</a></li>
                <li class="active"><a href="#">属性</a></li>
                <li><a href="<?= Url::to(['directive', 'id' => $id]) ?>">命令</a></li>
            </ul>
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <th>服务</th>
                        <th>属性</th>
                        <th>上报值</th>
                    </tr>
                    <?php foreach ($services as $key => $value) : ?>
                        <?php foreach ($value['attr'] as $k => $v) : ?>
                            <tr>
                                <td><?= $k == 0 ? $value['title'] : '' ?></td>
                                <td><?= $v['title'] ?></td>
                                <td><?= $value['newAttr']['value'][$v['title']] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
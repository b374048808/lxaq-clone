<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 15:18:06
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-15 18:01:05
 * @Description: 
 */

use yii\widgets\LinkPager;
use common\helpers\BaseHtml as Html;

$this->title = '回收站';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>房屋</th>
                            <th>监测点位</th>
                            <th>数值</th>
                            <th>上传时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $model) { ?>
                            <tr id=<?= $model->id; ?>>
                                <td><?= $model->id; ?></td>
                                <td><?= $model->house->title; ?></td>
                                <td><?= $model->parent->title; ?></td>
                                <td><?= $model->value; ?></td>
                                <td><?= date('Y-m-d H:i:s', $model->event_time); ?></td>
                                <td>
                                    <?= Html::linkButton(['show', 'id' => $model->id], '还原'); ?>
                                    <?= Html::delete(['delete', 'id' => $model->id]); ?>
                                </td>
                            </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <?= LinkPager::widget([
                    'pagination' => $pages
                ]); ?>
            </div>
        </div>
    </div>
</div>
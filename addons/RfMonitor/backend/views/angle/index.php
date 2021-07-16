<?php

use common\helpers\Html;
use common\helpers\ImageHelper;
use yii\widgets\LinkPager;

$this->title = '倾斜数据';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>


<div class="row">
    <div class="col-xs-12">
        <div class="box">

            <div class="box-body">
                <div class="box-header">
                    <h3 class="box-title">点位分布</h3>
                </div>
                <?php foreach ($pidModel->covers ?: [] as $key => $value) : ?>
                    <div class="col-md-2">
                        <?= ImageHelper::fancyBox($value,'100%','auto'); ?>
                    </div>
                <?php endforeach; ?>
            </div>
            <div class="box-body table-responsive">

                <div class="box-header">
                    <h3 class="box-title"><?= $this->title; ?></h3>
                    <div class="box-tools">
                        <?= Html::create(['ajax-edit', 'pid' => $pid], '创建', [
                            'data-toggle' => 'modal',
                            'data-target' => '#ajaxModal',
                        ]); ?>
                    </div>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr>
                            <th>点位</th>
                            <th>首次水平</th>
                            <th>第二水平</th>
                            <th>水平差距</th>
                            <th>首次垂直</th>
                            <th>第二垂直</th>
                            <th>垂直差距</th>
                            <th>倾斜率</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $model) { ?>
                            <td><?= $model->title; ?></td>
                            <td><?= $model->level_first ?></td>
                            <td><?= $model->level_second ?></td>
                            <td class="blue"><?= $model->level ?></td>
                            <td><?= $model->vertical_first; ?></td>
                            <td><?= $model->vertical_second; ?></td>
                            <td class="blue"><?= $model->vertical; ?></td>
                            <td class="orange"><?= $model->value; ?></td>
                            <td>
                                <?= Html::edit(['ajax-edit', 'id' => $model['id']], '编辑', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModal',
                                ]); ?>
                                <?= Html::status($model['status']); ?>
                                <?= Html::delete(['delete', 'id' => $model['id']]); ?>
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
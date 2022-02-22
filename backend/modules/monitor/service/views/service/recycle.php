<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-07 15:18:06
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 15:36:52
 * @Description: 
 */

use common\enums\VerifyEnum;
use yii\widgets\LinkPager;
use common\helpers\BaseHtml as Html;

$this->title = '回收站';
$this->params['breadcrumbs'][] = ['label' => '任务列表','url' => ['/monitor-service/service/index']];
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
                            <th>关联项目</th>
                            <th>发布者</th>
                            <th>负责人</th>
                            <th>审核状态</th>
                            <th>发布时间</th>
                            <th>操作</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($models as $model) { ?>
                            <tr id=<?= $model->id; ?>>
                                <td><?= $model->id; ?></td>
                                <td><?= $model->item->title; ?></td>
                                <td><?= $model->user->realname; ?></td>
                                <td><?= $model->member->realname; ?></td>
                                <td><?= VerifyEnum::getValue($model->audit); ?></td>
                                <td><?= date('Y-m-d H:i:s', $model->created_at); ?></td>
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
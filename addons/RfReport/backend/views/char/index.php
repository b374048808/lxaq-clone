<?php

use addons\RfReport\common\models\Char;
use common\helpers\Html;
use yii\widgets\LinkPager;
use common\helpers\Url;

$this->title = '字符管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>


<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>标题</th>
                        <th>类型</th>
                        <th>字符</th>
                        <th>排序</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id = <?= $model->id; ?>>
                            <td><?= $model->id; ?></td>
                            <td><?= $model->title; ?></td>
                            <td><?= Char::$typeMap[$model->type]; ?></td>
                            <td><?= $model->char; ?></td>
                            <td class="col-md-1"><?= Html::sort($model['sort']); ?></td>
                            <td>
                                <?= Html::edit(['ajax-edit','id' => $model['id']], '编辑', [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
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
                ]);?>
            </div>
        </div>
    </div>
</div>

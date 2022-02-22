<?php

use common\enums\AttrTypeEnum;
use common\enums\StatusEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\Url;

$this->title = $model['name'];
$this->params['breadcrumbs'][] = ['label' => '产品列表', 'url' => Url::to(['/console-huawei/product/index'], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<style>
    .panel-delete-right {
        float: right;
        font-size: 12px;
    }
</style>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#">概况</a></li>
                <li><a href="<?= Url::to(['/console-huawei/directive/index', 'pid' => $model['id']]) ?>">命令</a></li>
                <li class="pull-right">
                    <?= Html::linkButton(['ajax-edit', 'id' => $model['id']], '<i class="fa fa-edit" ></i> 编辑', [
                        'class' => 'btn btn-info btn-xs',
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg'
                    ]); ?>
                </li>
            </ul>
            <div class="box-body">
                <table class="table table-hover">
                    <tr>
                        <td>产品名称</td>
                        <td><?= $model->name ?></td>
                        <td>产品key</td>
                        <td><?= $model->product_key ?></td>
                    </tr>
                    <tr>
                        <td>产品类型</td>
                        <td><?= $model->type ?></td>
                        <td>产家名称</td>
                        <td><?= $model->producers ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title">服务</h3>
                <div class="box-tools">
                    <?= Html::create(['/console-huawei/service/ajax-edit', 'pid' => $model['id']], '添加', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body">
                <div class="box-body">
                    <div class="panel-group">
                        <?php foreach ($services as $key => $value) : ?>
                            <div class="panel panel-info">
                                <div class="panel-heading" role="tab" id="heading<?= $key ?>">
                                    <h4 class="panel-title">
                                        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse<?= $key ?>" aria-expanded="<?= $key == 0 ? true : false; ?>" aria-controls="collapse<?= $key ?>">
                                            <?= $value['title'] ?>
                                        </a>
                                        <?= Html::a('<i class="fa fa-edit blue" style="font-size:8px"></i>',['/console-huawei/service/ajax-edit', 'id' => $value['id']], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]) ?>
                                        <?= Html::a('删除服务', ['/console-huawei/service/delete', 'id' => $value['id']], $options = ['onclick' => "rfDelete(this);return false;", 'class' => 'red panel-delete-right']) ?>
                                    </h4>
                                </div>
                                <div id="collapse<?= $key ?>" class="panel-collapse collapse <?= $key == 0 ? 'in' : ''; ?>" role="tabpanel" aria-labelledby="heading<?= $key ?>">
                                    <div class="panel-body">
                                        <?= Html::linkButton(['/console-huawei/service-attr/ajax-edit', 'pid' => $value['id']], '添加属性', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]) ?>
                                        <table class="table table-hover">
                                            <tr>
                                                <th>属性名称</th>
                                                <th>数据类型</th>
                                                <th>描述</th>
                                                <th>操作</th>
                                            </tr>
                                            <?php foreach ($value['attr'] as $k => $v) : ?>
                                                <tr>
                                                    <td><?= $v['title'] ?></td>
                                                    <td><?= AttrTypeEnum::getValue($v['type']) ?></td>
                                                    <td><?= $v['description'] ?></td>
                                                    <td>
                                                        <?= Html::a('编辑', ['/console-huawei/service-attr/ajax-edit', 'id' => $v['id']], $options = [
                                                            'data-toggle' => 'modal',
                                                            'data-target' => '#ajaxModalLg',
                                                            'class' => 'purple'
                                                        ]) ?>
                                                        <?= Html::a('删除', ['/console-huawei/service-attr/delete', 'id' => $v['id']], $options = ['class' => 'red',]) ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
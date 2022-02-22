<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 14:38:49
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 16:05:04
 * @Description: 
 */

use common\enums\monitor\ItemStepsEnum;
use common\enums\monitor\ItemTypeEnum;
use common\enums\VerifyEnum;
use common\helpers\BaseHtml;
use common\helpers\Url;
use common\helpers\ImageHelper;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = $model['title'];
$this->params['breadcrumbs'][] = ['label' => '项目列表', 'url' => Url::to(['/monitor-project/item/index'])];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<?= Html::cssFile('@web/resources/css/jquery.step.css') ?>
<?= Html::jsFile('@web/resources/js/jquery.step.min.js') ?>
<style>
    button {
        display: inline-block;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        text-align: center;
        cursor: pointer;
        border: 1px solid transparent;
        border-radius: 4px;
        color: #fff;
        background-color: #5bc0de;
    }

    .btns {
        float: left;
    }

    .info {
        float: left;
        height: 34px;
        line-height: 34px;
        margin-left: 40px;
        font-size: 28px;
        font-weight: bold;
        color: #928787;
    }

    .info span {
        color: red;
    }
</style>

<div class="row">
    <div class="col-xs-12 col-md-8">
        <div class="nav-tabs-custom">
            <div class="box-header">
                <i class="fa fa-clone blue" style="font-size: 8px"></i>
                <h3 class="box-title">
                    <?= $model['title'] . '  ' . VerifyEnum::html($model['audit']) ?>
                    <span class="label label-info"><?= ItemTypeEnum::getValue($model['type']) ?></span>
                </h3>
                <p><?= $model['user']['realname'] . date('Y-m-d', $model['created_at']) ?></p>
                <div class="box-tools">
                    <?= BaseHtml::edit(['edit', 'id' => $model['id']], '<i class="fa fa-pencil-square-o" aria-hidden="true">编辑</i>') ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>归属人</td>
                        <td><?= $model['belonger'] ?: "未设置" ?></td>
                        <td>委托方</td>
                        <td><?= $model['entrust'] ?: "未设置" ?></td>
                        <td>金额</td>
                        <td><?= $model['money'] ?: "未设置" ?></td>
                        <td>编号</td>
                        <td><?= $model['number'] ?: "未设置" ?></td>

                    </tr>
                    <tr>
                        <td>省市区</td>
                        <td><?= Yii::$app->services->provinces->getCityListName([$model['province_id'], $model['city_id'], $model['area_id']]) ?: '未设置' ?></td>
                        <td>详细地址</td>
                        <td><?= $model['address'] ?: '未设置' ?></td>
                        <td>联系人</td>
                        <td><?= $model['contact'] ?: "未设置" ?></td>
                        <td>联系方式</td>
                        <td><?= $model['mobile'] ?: "未设置" ?></td>
                    </tr>
                    <tr>
                        <td>项目需求</td>
                        <td><?= $model['demand'] ?: "未设置" ?></td>
                        <td>概况</td>
                        <td><?= $model['survey'] ?: "未设置" ?></td>
                        <td>开始时间</td>
                        <td><?= date('Y-m-d', $model['start_time']) ?></td>
                        <td>结束时间</td>
                        <td><?= date('Y-m-d', $model['end_time']) ?></td>
                    </tr>
                    <tr>
                        <td>附件</td>
                        <td colspan="3">
                            <?php foreach ($model->file ?: [] as $key => $value) : ?>
                                <?= Html::a('点击下载', $value, $options = ['download' => 'download']) ?>
                            <?php endforeach; ?>
                        </td>
                        <td>附件图片</td>
                        <td colspan="3">
                            <?php ImageHelper::fancyBoxs($model['images'] ?: []) ?>
                        </td>
                    </tr>
                    <tr>
                        <td>描述</td>
                        <td colspan="3"><?= $model['description'] ?: '未设置' ?></td>
                        <td>备注</td>
                        <td colspan="3"><?= $model['remark'] ?: '未设置' ?></td>
                    </tr>
                    <tr>
                        <td>进度</td>
                        <td colspan="7">
                            <?php if ($model['audit'] == VerifyEnum::PASS) : ?>
                                <?= VerifyEnum::getValue($model['audit']) ?>
                                <?php foreach (ItemStepsEnum::getMap() ?: [] as $key => $value) : ?>
                                    <?= $key <= $model['steps'] ? '->' . $value : '' ?>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <?= VerifyEnum::getValue($model['audit']) ?>
                            <?php endif; ?>
                            <!-- <div class="main-step">
                                <div id="step"></div>
                            </div> -->
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- 合同列表 -->
        <div class="nav-tabs-custom">
            <div class="box-header">
                <i class="fa fa-file-text blue" style="font-size: 8px"></i>
                <h3 class="box-title">关联合同</h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-project/item-contract/ajax-edit', 'pid' => $model['id']], '添加', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>经办人员</th>
                        <th>金额</th>
                        <th>签约日期</th>
                        <th>审核状态</th>
                        <th>操作</th>
                    </tr>
                    <?php foreach ($model->contract ?: [] as $key => $value) : ?>
                        <tr>
                            <td><?= $value['member']['realname'] ?></td>
                            <td><?= $value['money'] ?></td>
                            <td><?= date('Y-m-d', $value['event_time']) ?></td>
                            <td><?= VerifyEnum::html($value['audit']) ?></td>
                            <td>
                                <?= Html::a('详情', ['/monitor-project/item-contract/view', 'id' => $value['id']], $options = [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ]) ?>
                                <?= Html::a('编辑', ['/monitor-project/item-contract/ajax-edit', 'id' => $value['id']], $options = [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ]) ?>
                                <?= Html::a('删除', ['/monitor-project/item-contract/delete', 'id' => $value['id']], $options = []) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        <!-- 任务列表 -->
        <div class="nav-tabs-custom">
            <div class="box-header">
                <i class="fa fa-tasks blue" style="font-size: 8px"></i>
                <h3 class="box-title">关联任务</h3>
                <div class="box-tools">
                    <?= Html::create(['/monitor-service/service/ajax-edit', 'pid' => $model['id']], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>负责人</th>
                        <th>结束时间</th>
                        <th>状态</th>
                        <th>描述</th>
                        <th>操作</th>
                    </tr>
                    <?php foreach ($model->services ?: [] as $key => $value) : ?>
                        <tr>
                            <td><?= $value['member']['realname'] ?></td>
                            <td><?= date('Y-m-d', $value['end_time']) ?></td>
                            <td><?= VerifyEnum::html($value['audit']) ?></td>
                            <td><?= $value['description'] ?></td>
                            <td>
                                <?= Html::a('详情', ['/monitor-service/service/view', 'id' => $value['id']]) ?>
                                <?= Html::a('编辑', ['/monitor-service/service/edit', 'id' => $value['id']], $options = []) ?>
                                <?= Html::a('删除', ['/monitor-service/service/delete', 'id' => $value['id']], $options = []) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-md-4">
        <div class="nav-tabs-custom">
            <div class="box-header">
                <i class="fa fa-home blue" style="font-size: 8px"></i>
                <h3 class="box-title">关联房屋</h3>
                <div class="box-tools">
                    <?= html::linkButton(['/monitor-project/item-map/ajax-house', 'id' => $model['id']], '添加关联', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">

                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    // 'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'points',
                        ],
                        'house.title',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{destroy}',
                            'buttons' => [
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['/monitor-project/item-map/delete', 'item_id' => $model['item_id'], 'house_id' => $model['house_id']]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

                <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>
            </div>
        </div>
        <div class="nav-tabs-custom">
            <div class="box-header">
                <h3 class="box-title">审批记录</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th>时间</th>
                        <th>说明</th>
                        <th>备注</th>
                    </tr>
                    <?php foreach ($model->auditLog ?: [] as $key => $value) : ?>
                        <tr>
                            <td><?= date('Y-m-d', $value['created_at']) ?></td>
                            <td><?= $value['remark'] ?></td>
                            <td><?= $value['description'] ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</div>
<?php
$url = Url::to(["/monitor-project/item-map/del-house", 'id' => $model['id']]);

$js = <<<JS
  
    $(".checkDelete").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        $.ajax({
            url:"$url",
            type:"post",
            data:{data:keys},
            dataType:"json",
            success:function(e){
                e?location.reload():alert('更新失败!');
            }
        })
    });
JS;
$this->registerJs($js);

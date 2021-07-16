<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 11:36:17
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 15:47:11
 * @Description: 
 */

use common\enums\StatusEnum;
use common\helpers\Url;
use common\helpers\Html;
use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use yii\widgets\ActiveForm;
use common\helpers\ImageHelper;

$this->title = '报警详情';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => Url::to(['/monitor-project/item/index'])];
$this->params['breadcrumbs'][] = ['label' => $model->point->title, 'url' => Url::to(['/monitor-project/point/view', 'id' => $model['pid']], $schema = true)];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">

    <div class="col-xs-4">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-circle" style="font-size: 8px"></i>
                <h3 class="box-title">报警</h3>
                <div class="box-tools">
                    <?= Html::edit(['ajax-edit', 'id' => $model['id']], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                    <?= Html::linkButton(['log-view', 'id' => $model['id']], '<i class="fa fa-history" style="font-size:8px"></i> 日志', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <td>报警等级</td>
                        <td><?= WarnEnum::$spanlistExplain[$model['warn']] ?></td>
                    </tr>
                    <tr>
                        <td>时间</td>
                        <td><?= date('Y-m-d H:i:s', $model['created_at']) ?></td>
                    </tr>
                    <tr>
                        <td>处理方式</td>
                        <td><?= WarnStateEnum::getValue($model['state']) ?></td>
                    </tr>
                    <tr>
                        <td>时间</td>
                        <td><?= StatusEnum::getValue($model['status']) ?></td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <div class="col-xs-8">
        <div class="box">
            <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">处理反馈</h3>
                <div class="box-tools">

                </div>
            </div>
            <div class="box-body table-responsive">
                <table class="table table-hover">
                    <tr>
                        <th style="width:180px">时间</th>
                        <th>人员</th>
                        <th>内容</th>
                        <th>操作</th>
                    </tr>
                    <?php foreach ($data as $key => $value) : ?>
                        <tr>
                            <td style="vertical-align:top">
                                <?= date('Y-m-d H:i:s', $value['created_at']) ?>
                            </td>
                            <td style="vertical-align:top;">
                                <?= ImageHelper::fancyBox(ImageHelper::defaultHeaderPortrait($value['user']['head_portrait']),20,20) ?>
                                <?= $value['user']['username'] ?>
                            </td>
                            <td>
                            <?= $value['description'] ?>
                            </td>

                            <td style="vertical-align:top;">
                                <?= Html::a('编辑', ['/monitor-project/feedback/ajax-edit','id' => $value['id']], $options = [
                                    'data-toggle' => 'modal',
                                    'data-target' => '#ajaxModalLg',
                                ]) ?>
                                <?= Html::a('删除', ['/monitor-project/feedback/destroy','id' => $value['id']], $options = [
                                    'class' => 'red'
                                ]) ?>

                            </td>
                        </tr>
                        <?php if(isset($value['files'])): ?>
                        <tr>
                            <td colspan="4">
                                附件：
                                <?php foreach (json_decode($value['files']) as $k => $val) : ?>
                                    <?php
                                    switch (pathinfo($val)['extension']) {
                                        case 'jpg':
                                            echo '<a href="' . $val . '"  data-fancybox data-caption="' . pathinfo($val)['basename'] . '">
                                                    <i class="fa fa-image"></i>
                                                </a>';
                                            break;
                                        case 'png':
                                            echo '<a href="' . $val . '"  data-fancybox data-caption="' . pathinfo($val)['basename'] . '">
                                            <i class="fa fa-image"></i>
                                                </a>';
                                            break;
                                        case 'mp4':
                                            echo '<video src="' . $val . '" controls="controls" width="20px" height="20px"><a href="' . $val . '">asd</a></video>';
                                            break;

                                        default:
                                            echo '<a href="' . $val . '" class="btn btn-white btn-sm">' . pathinfo($val)['basename'] . '</a>';
                                            break;
                                    }
                                    ?>
                                <?php endforeach ?>
                            </td>
                        </tr>
                        <?php endif; ?>
                    <?php endforeach ?>
                </table>
                <hr>
                <div class="box-header">
                    <h3 class="box-title">反馈信息</h3>
                </div>
                <?php $form = ActiveForm::begin([
                    'id' => $model->formName(),
                    'enableAjaxValidation' => true,
                    'validationUrl' => Url::to(['view', 'id' => $model['id']]),
                    'fieldConfig' => [
                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                    ]
                ]);
                ?>
                <div class="modal-body">
                    <?= $form->field($fileModel, 'pid')->hiddenInput()->label(false) ?>
                    <?= $form->field($fileModel, 'files')->widget(common\widgets\webuploader\Files::class, [
                        'config' => [
                            'pick' => [
                                'multiple' => true,
                            ],
                            'formData' => [
                                // 不配置则不生成缩略图
                                'thumb' => [
                                    [
                                        'width' => 100,
                                        'height' => 100,
                                    ],
                                    [
                                        'width' => 200,
                                        'height' => 200,
                                    ],
                                ],
                                'drive' => 'local', // 默认本地 支持 qiniu/oss/cos 上传
                            ],
                        ]
                    ]); ?>
                    <?= $form->field($fileModel, 'description')->textarea() ?>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="submit">提交</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
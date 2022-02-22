<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:31:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-30 12:04:00
 * @Description: 
 */

use common\enums\VerifyEnum;
use common\helpers\BaseHtml;
use common\models\worker\Worker;
use yii\grid\GridView;

$this->title = '报告审核记录';
$this->params['breadcrumbs'][] = ['label' => '报告列表','url' => ['/monitor-project/report/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'header' => '对应房屋',
                            'attribute'   => 'report.house.title'
                        ],
                        'report.file_name',
                        [
                            'attribute' => 'verify',
                            'value' => function ($queue) {
                                return VerifyEnum::getAudit($queue['verify']);
                            },
                            'filter' => [
                                VerifyEnum::PASS => '通过审核',
                                VerifyEnum::WAIT => '提交',
                                VerifyEnum::SAVE => '撤回',
                                VerifyEnum::OUT => '审核驳回',
                            ], //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '提交人',
                            'attribute'   => 'report.user.realname',
                            'filter' => Worker::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '审核人',
                            'attribute'   => 'member.realname',
                            'filter' => Worker::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        'description',
                        [
                            'header' => '时间',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ], 
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{destroy}',
                            'buttons' => [
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
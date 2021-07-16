<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-13 15:36:29
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-13 15:53:05
 * @Description: 
 */

use common\enums\WarnEnum;
use common\enums\WarnStateEnum;
use yii\grid\GridView;
use yii\widgets\Pjax;
use common\helpers\Html;
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <?php Pjax::begin(); ?>
    <?= GridView::widget([
        'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid-ajax'],
        'dataProvider' => $dataProvider,
        //重新定义分页样式
        // 'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            [
                'attribute' => 'warn',
                'value' => function($model){
                    return WarnEnum::getValue($model->warn);
                },
                'filter' => true, //不显示搜索框
                'format' => 'raw',
            ],
            [
                'attribute' => 'state',
                'value' => function($model){
                    return WarnStateEnum::getValue($model->state);
                },
                'filter' => true, //不显示搜索框
                'format' => 'raw',
            ],
            'remark',
            [
                'attribute' => 'created_at',
                'filter' => false, //不显示搜索框
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],

        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>
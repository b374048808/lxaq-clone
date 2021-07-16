<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 13:49:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 15:45:09
 * @Description: 
 */

use common\enums\WarnStateEnum;
use common\enums\WarnEnum;
use yii\grid\GridView;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">日志</h4>
</div>
<div class="modal-body">
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        //重新定义分页样式
        'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
            ],
            'user.username',
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'attribute' => 'warn',
                'value' => function ($model) {
                    return WarnEnum::$spanlistExplain[$model->warn];
                },
                'format' => 'raw',
            ],
            [
                'attribute' => 'state',
                'value' => function ($model) {
                    return WarnStateEnum::getValue($model['state']);
                },
                'format' => 'raw',
            ]
        ],
    ]); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
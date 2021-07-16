<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 13:49:42
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 09:39:28
 * @Description: 
 */

use common\enums\WarnStateEnum;
use common\enums\WarnEnum;
use yii\grid\GridView;
use common\helpers\Html;

?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
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
            [
                'attribute' => 'created_at',
                'format' => ['date', 'php:Y-m-d H:i:s'],
            ],
            [
                'header' => "报警等级",
                'attribute' => 'warn',
                'value' => function ($model) {
                    return WarnEnum::$spanlistExplain[$model->warn];
                },
                'format' => 'raw',
            ],
            [
                'header' => "处理方式",
                'attribute' => 'state',
                'value' => function ($model) {
                    return WarnStateEnum::getValue($model['state']);
                },
                'format' => 'raw',
            ],
            [
                'header' => "操作",
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'buttons' => [
                    'view' => function ($url, $model, $key) {
                        return Html::a('查看', ['view', 'id' => $model['id']], $options = []);
                    },
                ],
            ],
        ],
    ]); ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>
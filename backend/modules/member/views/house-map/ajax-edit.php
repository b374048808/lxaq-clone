<?php

use common\enums\PointEnum;
use yii\widgets\ActiveForm;
use common\helpers\Url;
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
    <div class="row">
        <?= Html::beginForm(['ajax-edit', 'member_id' => $member_id], 'get', ['data-pjax' => '', 'class' => 'form-inline']); ?>
        <?= Html::input('text', 'title', Yii::$app->request->get('title'), ['class' => 'form-control']) ?>
        <?= Html::submitButton('<i class="fa fa-search"></i> 搜索', ['class' => 'btn btn-primary', 'name' => 'hash-button']) ?>
        <?= Html::endForm() ?>
        <h3><?= $stringHash ?></h3>

    </div>
    <?= GridView::widget([
        'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid-ajax'],
        'dataProvider' => $dataProvider,
        //重新定义分页样式
        // 'tableOptions' => ['class' => 'table table-hover'],
        'columns' => [
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'points',
            ],
            'title',
            [
                'attribute' => 'type',
                'filter' => false, //不显示搜索框
                'value' => function ($model) {
                    return PointEnum::getValue($model->type);
                },
                'format' => 'raw',
            ],
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <?= Html::a('批量添加', "javascript:void(0);", ['class' => 'btn btn-success checkStart']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>


<?php
$url = Url::to(["add-house", 'member_id' => $member_id]);
$js = <<<JS
    $(".checkStart").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid-ajax").yiiGridView("getSelectedRows");
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

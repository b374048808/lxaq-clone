<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-19 14:08:58
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-23 15:13:52
 * @Description: 
 */

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
        <?= Html::beginForm(['ajax-edit', 'ground_id' => $ground_id], 'get', ['data-pjax' => '', 'class' => 'form-inline']); ?>
        <?= Html::input('number', 'number', Yii::$app->request->get('number'), ['class' => 'form-control']) ?>
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
                'class' => 'yii\grid\SerialColumn',
                'headerOptions' => ['width' => '20px'],
            ],
            [
                'class' => 'yii\grid\CheckboxColumn',
                'name' => 'points',
            ],
            'number',
        ],
    ]); ?>
    <?php Pjax::end(); ?>
    <?= Html::a('批量添加', "javascript:void(0);", ['class' => 'btn btn-success checkStart']) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
</div>


<?php
$url = Url::to(["add-house", 'ground_id' => $ground_id]);
$successUrl = Url::to(["index", 'id' => $ground_id]);
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
                // e?window.history.back(-1):alert('更新失败!');
                e?window.location.href="$successUrl":alert('更新失败!');
            }
        })
    });
JS;
$this->registerJs($js);

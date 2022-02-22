<?php

use common\enums\device\SwitchEnum;
use common\helpers\BaseHtml;
use common\helpers\Html;
use common\helpers\Url;
use yii\grid\GridView;
use common\models\console\iot\huawei\Product;

$this->title = '设备列表';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>
<script>
    function func() {
        var form = document.getElementById("ruleForm"); //获取form表单对象
        form.submit(); //form表单提交
    };
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= BaseHtml::create(['ajax-edit', 'cate_id' => $cate_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                    <?= BaseHtml::linkButton(['recycle'], '<i class="fa fa-trash"></i> 回收站'); ?>
                </div>
            </div>
            <div class="row" style="margin-top: 25px;">
                <div class="col-xs-8">
                    <div class="col-xs-4">
                        <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">设备总数</p>
                        <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $dataProvider->totalCount ?></p>
                    </div>
                    <div class="col-xs-4">
                        <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">在线设备</p>
                        <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $onLine ?></p>
                    </div>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\CheckboxColumn',
                            'name' => 'points',
                        ],
                        [
                            'attribute' => 'number',
                            'value' => function ($model) {
                                return $model['number'] . ' ' . $model->deviceStatus . ' ' . $model->deviceVoltage;
                            },
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '所属产品',
                            'attribute' => 'pid',
                            'value' => 'product.name',
                            'filter' => Product::getMapList(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '开启',
                            'attribute' => 'switch',
                            'value' => function ($model) {
                                return Html::switch($model['switch']);
                            },
                            'filter' => SwitchEnum::getMap(), //不显示搜索框
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'last_time',
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        'card',
                        [

                            'attribute' => 'over_time',
                            'format' => ['date', 'php:Y-m-d'],
                            'filter' => false,
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {destroy}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return BaseHtml::edit(['view', 'id' => $model->id], '查看');
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['destroy', 'id' => $model->id]);
                                },
                            ]
                        ]
                    ],
                ]); ?>

                <?= Html::a('批量删除', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>
                <?= Html::a('下发指令', "javascript:void(0);", [
                    'class' => 'btn btn-info',
                    'data-toggle' => 'modal',
                    'data-target' => '#myModal',
                ]) ?>

            </div>
        </div>
    </div>
</div>
<!-- 动态模糊框写入指令 -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">命令输入框</h4>
            </div>
            <div class="modal-body">
                <?= Html::input('text', 'post-content', '', ['class' => 'form-control', 'placeholder' => '命令16进制']); ?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">关闭</button>
                <?= Html::a('下发指令', "javascript:void(0);", [
                    'class' => 'btn btn-info checkDirective',
                ]) ?>
            </div>
        </div>
    </div>
</div>
<script>
    // 小模拟框清除
    // 启用状态 status 1:启用;0禁用;
    function rfSwitch(obj) {
        let id = $(obj).attr('data-id');
        let status = 1;
        self = $(obj);
        if (self.hasClass("btn-success")) {
            status = 0;
        }

        if (!id) {
            id = $(obj).parent().parent().attr('id');
        }

        if (!id) {
            id = $(obj).parent().parent().attr('data-key');
        }

        $.ajax({
            type: "get",
            url: "<?= Url::to(['ajax-update']) ?>",
            dataType: "json",
            data: {
                id: id,
                switch: status
            },
            success: function(data) {
                console.log(data)
                if (parseInt(data.code) === 200) {
                    if (self.hasClass("btn-success")) {
                        self.removeClass("btn-success").addClass("btn-danger");
                        self.attr("data-toggle", 'tooltip');
                        self.attr("data-original-title", '点击关闭');
                        self.text('关闭');
                    } else {
                        self.removeClass("btn-danger").addClass("btn-success");
                        self.attr("data-toggle", 'tooltip');
                        self.attr("data-original-title", '点击启用');
                        self.text('启用');
                    }
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>
<?php
$deleteUrl = Url::to(["delete-all"]);
$directiveUrl = Url::to(["directive-all"]);
$js = <<<JS
  
    $(".checkDelete").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        $.ajax({
            url:"$deleteUrl",
            type:"post",
            data:{data:keys},
            dataType:"json",
            success:function(e){
                e?location.reload():alert('更新失败!');
            }
        })
    });
    $(".checkDirective").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        var content =  $("input[name=post-content]").val() ;
        if (keys.length>0 && content) {
            $.ajax({
                url:"$directiveUrl",
                type:"post",
                data:{data:keys,content:content},
                dataType:"json",
                success:function(e){
                    console.log(e.message);
                    e?console.log(e):alert('更新失败!');
                }
            })            
        }else{
            alert('设备未选择或命令为空！');
        }
    });
JS;
$this->registerJs($js);

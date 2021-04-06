<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\Url;
use common\models\iot\ali\Product;
use yii\widgets\ActiveForm;

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
    <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['ali-device/index']) ?>"> 华为云设备</a></li>
                <li class="active"><a href="#"> 阿里设备</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit', 'cate_id' => $cate_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
            <div class="row" style="margin-top: 25px;">
                <div class="col-xs-8">
                    <div class="col-xs-4">
                        <?php
                        $form = ActiveForm::begin([
                            'id' => 'ruleForm',
                            'method' => 'GET',
                            'validationUrl' => Url::to(['index']),
                        ]);
                        echo $form->field($searchModel, 'pid', ['options' => ['onchange' => 'func()']])->dropDownList(Product::getMapList(), ['prompt' => '请选择产品!'])->label('产品类型');
                        ActiveForm::end();
                        ?>
                    </div>
                    <div class="col-xs-4">
                        <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">设备总数</p>
                        <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $dataProvider->totalCount ?></p>
                    </div>
                    <div class="col-xs-4">
                        <p style="font-size: 12px;color: #888;display: flex;justify-content: flex-start;margin-bottom: 4px;">在线设备</p>
                        <p style="font-size: 24px;color: #373d41;margin-top: #333;line-height: 1;"><?= $deviceCount ?></p>
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
                            'name' => 'id',
                        ],
                        'device_name',
                        [
                            'header' => '所属产品',
                            'attribute' => 'product_name',
                            'value' => 'product.name',
                            'filter' => true, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '状态/启用状态',
                            'value' => function ($model) {
                                return $model->status ? '开启' : '禁止';
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '最后上线时间',
                            'attribute' => 'newdata_created_at',
                            'value' => 'newdata.created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view} {delete}',
                        ],
                    ],
                ]); ?>
                <?= Html::a('批量启动', "javascript:void(0);", ['class' => 'btn btn-success checkStart']) ?>
                <?= Html::a('批量禁止', "javascript:void(0);", ['class' => 'btn btn-danger checkDelete']) ?>
            </div>
        </div>
    </div>
</div>
<?php
$url = Url::to(["status-value"]);
$js = <<<JS
    $(".checkStart").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        $.ajax({
            url:"$url",
            type:"post",
            data:{data:keys,status:1},
            dataType:"json",
            success:function(e){
                e?location.reload():alert('更新失败!');
            }
        })
    });
    $(".checkDelete").on("click", function () {
        //注意这里的$("#grid")，要跟我们第一步设定的options id一致
        var keys = $("#grid").yiiGridView("getSelectedRows");
        $.ajax({
            url:"$url",
            type:"post",
            data:{data:keys,status:0},
            dataType:"json",
            success:function(e){
                e?location.reload():alert('更新失败!');
            }
        })
    });
    function func(){
        alert("asd");
        var form = document.getElementById("w0");//获取form表单对象
        form.submit();//form表单提交
    };
JS;
$this->registerJs($js);

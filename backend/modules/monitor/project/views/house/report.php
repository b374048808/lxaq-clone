<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-29 10:26:37
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-14 10:16:44
 * @Description: 
 */

use common\enums\monitor\ReportEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\models\monitor\project\House;
use yii\helpers\Url;

$this->title = '报告列表';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => Url::to(['/monitor-project/house/index'])];
$this->params['breadcrumbs'][] = ['label' => House::getTitle($id), 'url' => Url::to(['/monitor-project/house/view','id' => $pid])];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['/monitor-project/house/view', 'id' => $id], $schema = true) ?>">概况</a></li>
                <li><a href="<?= Url::to(['/monitor-project/point/index', 'pid' => $id], $schema = true) ?>">监测点</a></li>
                <li><a href="<?= Url::to(['/monitor-project/house/monitor', 'id' => $id], $schema = true) ?>">实时监测</a></li>
                <li><a href="<?= Url::to(['/monitor-project/house/data-chart', 'id' => $id], $schema = true) ?>">数据曲线</a></li>
                <li class="active"><a href="#">报告</a></li>
                <li class="pull-right">
                    <?= Html::create(['/monitor-project/report/ajax-edit', 'pid' => $id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'header' => '用户',
                            'attribute' => 'user.username',
                        ],
                        [
                            'attribute' => 'created_at',
                            'format' => ['date','php:Y-m-d H:i:s'],
                            'filter' => false
                        ],
                        [
                            'header' => '类型',
                            'attribute' => 'type',
                            'value' => function ($que) {
                                return ReportEnum::getMap()[$que->type];
                            },
                            'filter' => ReportEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'attribute' => 'files',
                            'value' => function ($que) {
                                $html = '';
                                foreach ($que['files'] as $key => $value) {
                                    $html.= Html::a('<i class="fa fa-file-word-o" style="font-size:18px"></i> ', $value,$options = [
                                        'download' => 'download'
                                    ]);
                                }
                                return $html;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {destroy}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['/monitor-project/report/ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['/monitor-project/report/destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
<script>
function aa(src,imgname){
    var alink=document.createElement("a");
    alink.href=src;
    alink.download=imgname;
    alink.click();
    alink.remove();
}
</script>
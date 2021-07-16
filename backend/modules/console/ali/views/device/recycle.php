<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-07-16 09:10:31
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-16 09:15:21
 * @Description: 
 */

use yii\grid\GridView;
use common\models\console\iot\ali\Product;
use common\models\monitor\project\point\AliMap;
use common\helpers\BaseHtml;

$this->title = '回收站';
$this->params['breadcrumbs'][] = ['label' =>'设备列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'options' => ['class' => 'grid-view', 'style' => 'overflow:auto', 'id' => 'grid'],
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        'device_name',
                        'number',
                        [
                            'header' => '所属产品',
                            'attribute' => 'pid',
                            'value' => 'product.name',
                            'filter' => Product::getMapList(), //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '关联监测点数量',
                            'value' => function ($model) {
                                return AliMap::getPointCount($model['id']);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'html',
                        ],
                        [
                            'header' => '状态',
                            'value' => function ($model) {
                                return $model->deviceStatus;
                            },
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
                            'attribute' => 'newValue_created_at',
                            'value' => 'newValue.created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{show} {delete}',
                            'buttons' => [
                                'show' => function ($url, $model, $key) {
                                    return BaseHtml::a('还原', ['show','id' => $model['id']]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return BaseHtml::delete(['delete','id' => $model['id']]);
                                },
                            ]
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>
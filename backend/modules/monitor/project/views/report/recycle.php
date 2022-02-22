<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:18:34
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-02 09:55:16
 * @Description: 
 */

use common\enums\VerifyEnum;
use yii\grid\GridView;
use common\helpers\Html;

$this->title = '回收站';

$this->params['breadcrumbs'][] = ['label' => '报告列表', 'url' => ['index']];
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
                        'user.realname',
                        'file_name',
                        [
                            'attribute' => 'verify',
                            'value' => function($queue){
                                return VerifyEnum::getValue($queue['verify']);
                            },
                            'filter' => VerifyEnum::getMap(), //不显示搜索框
                            'format' => 'html',
                        ],
                        'description',
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{show} {delete}',
                            'buttons' => [
                                'show' => function ($url, $model, $key) {
                                    return Html::a('还原', ['show','id' => $model['id']], $options = [
                                        'class' => 'blue'
                                    ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model->id],'删除',[
                                        'class' => 'red'
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>

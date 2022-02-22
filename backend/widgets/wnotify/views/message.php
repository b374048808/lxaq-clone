<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-11-18 09:01:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-18 11:03:58
 * @Description: 
 */

use yii\grid\GridView;

$this->title = '私信列表';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-2">
        <div class="box box-solid p-xs rfAddonMenu">
            <div class="box-header with-border">
                <h3 class="rf-box-title">消息提醒</h3>
            </div>
            <div class="box-body no-padding">
                <?= $this->render('_nav',['id' => $id]) ?>
            </div>
        </div>
    </div>
    <div class="col-sm-10">
        <div class="box">
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        [
                            'label' => '来自',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return $model->member_id > 0?$model->notifySenderForMember->senderForMember->realname:'';
                            }
                        ],
                        'notify.content',
                        [
                            'label' => '创建时间',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'label' => '查看时间',
                            'attribute' => 'updated_at',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return Yii::$app->formatter->asRelativeTime($model['updated_at']);
                            },
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
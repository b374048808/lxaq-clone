<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-23 14:15:28
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-14 09:52:04
 * @Description: 
 */

use common\helpers\Url;

$this->title = '实时监测';
$this->params['breadcrumbs'][] = ['label' => '房屋列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model['title'], 'url' => ['view','id' => $model['id']]];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-xs-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['view', 'id' => $model['id']], $schema = true) ?>">概况</a></li>
                <li><a href="<?= Url::to(['/monitor-project/point/index', 'pid' => $model['id']], $schema = true) ?>">监测点</a></li>
                <li class="active"><a href="#">实时监测</a></li>
                <li><a href="<?= Url::to(['/monitor-project/house/data-chart', 'id' => $model['id']], $schema = true) ?>">数据曲线</a></li>
                <li><a href="<?= Url::to(['report', 'id' => $model['id']], $schema = true) ?>">报告</a></li>
            </ul>
            <div class="box-body table-responsive">
                <div class="row">
                    <?php foreach ($points as $key => $value) : ?>
                        <div class="col-md-6">
                            <?= $this->render('_chart', ['id' => $model['id'], 'type' => $value]) ?>
                        </div>
                    <?php endforeach; ?>

                </div>
            </div>
        </div>
    </div>
</div>
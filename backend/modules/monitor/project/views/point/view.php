<?php
use common\helpers\Url;
use common\helpers\Html;

$this->title = '系统探针';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
        <div class="box-header">
                <i class="fa fa-circle blue" style="font-size: 8px"></i>
                <h3 class="box-title">数据曲线图</h3>
                <div class="box-tools">
                    <?= Html::linkButton(['/monitor-project/point-value/index', 'pid' => $model['id']], '<i class="icon ion-navicon"></i> 历史数据', [
                        'class' => 'btn btn-info btn-xs'
            		]); ?>
                </div>
            </div>
            
            <div class="box-body table-responsive">
                <table class="table">
                    <tr>
                        <td>总发送</td>
                        <td id="netWork_allOutSpeed"><?= $info['netWork']['allOutSpeed'] ?></td>
                        <td>总接收</td>
                        <td id="netWork_allInputSpeed"><?= $info['netWork']['allInputSpeed'] ?></td>
                    </tr>
                    <tr>
                        <td>发送速度</td>
                        <td id="netWork_currentOutSpeed"><?= $info['netWork']['currentOutSpeed'] ?></td>
                        <td>接收速度</td>
                        <td id="netWork_currentInputSpeed"><?= $info['netWork']['currentInputSpeed'] ?></td>
                    </tr>
                    <tr>
                        <td colspan="4">
                        <?= \common\widgets\echarts\Echarts::widget([
                'config' => [
                    'server' => Url::to(['value-between-chart','id' => $model->id]),
                    'height' => '400px'
                ]
            ]) ?>
                        </td>
                    </tr>
                </table>
            </div>
            
        </div>
    </div>
</div>
<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-08 09:32:48
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-23 11:03:35
 * @Description: 
 */

use yii\widgets\ActiveForm;
use common\helpers\Html;

$form = ActiveForm::begin([
    'options' => [
        'enctype' => 'multipart/form-data'
    ]
]);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
    <div class="form-group">
        <div class="input-group m-b">
            <?= Html::a('<i class="fa fa-cloud-download link"></i> 下载模板', ['download', 'pid' => $pointModel['id'], 'type' => $pointModel['type']], $options = []); ?>
        </div>
        <div class="input-group m-b">
            <input id="excel-file" type="file" name="excelFile" style="display:none">
            <input type="text" class="form-control" id="fileName" name="fileName" readonly>

            <span class="input-group-btn">
                <a class="btn btn-white" onclick="$('#excel-file').click();">选择文件</a>
            </span>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>
<script type="text/javascript">
    $('input[id=excel-file]').change(function() {
        $('#fileName').val($(this).val());
    });
</script>
<?php

namespace common\models\common;

use Yii;

/**
 * This is the model class for table "rf_lx_sms_warn_log".
 *
 * @property int $id
 * @property string $mobile 手机号码
 * @property string $number 设备编号
 * @property string $content 内容
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class WarnLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_sms_warn_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['error_code', 'status', 'created_at', 'updated_at'], 'integer'],
            [['error_data'], 'string'],
            [['mobile'], 'string', 'max' => 20],
            [['number'], 'string', 'max' => 100],
            [['content'], 'string', 'max' => 500],
            [['error_msg'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'mobile' => 'Mobile',
            'number' => 'Number',
            'content' => 'Content',
            'error_code' => 'Error Code',
            'error_msg' => 'Error Msg',
            'error_data' => 'Error Data',
            'status' => 'Status',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ];
    }
}

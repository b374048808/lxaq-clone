<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-28 09:17:46
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-28 09:53:57
 * @Description: 
 */

namespace common\models\common;

use Yii;
use common\behaviors\MerchantBehavior;

/**
 * This is the model class for table "{{%common_sms_log}}".
 *
 * @property int $id
 * @property string $merchant_id 商户id
 * @property string $member_id 用户id
 * @property string $mobile 手机号码
 * @property string $code 验证码
 * @property string $content 内容
 * @property int $error_code 报错code
 * @property string $error_msg 报错信息
 * @property string $error_data 报错日志
 * @property string $usage 用途
 * @property int $used 是否使用[0:未使用;1:已使用]
 * @property int $use_time 使用时间
 * @property int $status 状态(-1:已删除,0:禁用,1:正常)
 * @property string $created_at 创建时间
 * @property string $updated_at 修改时间
 */
class LxSmsLog extends \common\models\base\BaseModel
{

    const USAGE_LOGIN = 'login';
    const USAGE_REGISTER = 'register';
    const USAGE_UP_PWD = 'up-pwd';
    const USAGE_UP_MOBILE = 'up-mobile';

    /**
     * @var array
     */
    public static $usageExplain = [
        self::USAGE_LOGIN => '登录',
        self::USAGE_REGISTER => '注册',
        self::USAGE_UP_PWD => '重置密码',
        self::USAGE_UP_MOBILE => '修改手机号码',
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%lx_sms_log}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['member_id', 'error_code', 'used', 'mobile', 'code', 'use_time', 'status', 'created_at', 'updated_at'], 'integer'],
            [['error_data'], 'string'],
            [['usage'], 'string', 'max' => 20],
            [['content'], 'string', 'max' => 500],
            [['error_msg'], 'string', 'max' => 200],
            ['ip','string','max' => 30]
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'member_id' => '用户',
            'mobile' => '手机号码',
            'code' => '验证码',
            'content' => '内容',
            'error_code' => '状态Code',
            'error_msg' => '状态说明',
            'error_data' => '具体信息',
            'usage' => '用途',
            'used' => '是否使用',
            'use_time' => '使用时间',
            'ip' => 'ip',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }
}

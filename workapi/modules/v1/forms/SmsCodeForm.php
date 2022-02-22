<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-27 10:26:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-28 09:35:32
 * @Description: 
 */

namespace workapi\modules\v1\forms;

use Yii;
use yii\base\Model;
use common\helpers\RegularHelper;
use common\models\common\LxSmsLog;

/**
 * Class SmsCodeForm
 * @package workapi\modules\v1\forms
 * @author jianyan74 <751393839@qq.com>
 */
class SmsCodeForm extends Model
{
    /**
     * @var
     */
    public $mobile;

    /**
     * @var
     */
    public $usage;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['mobile', 'usage'], 'required'],
            [['usage'], 'in', 'range' => array_keys(LxSmsLog::$usageExplain)],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '请输入正确的手机号'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'mobile' => '手机号码',
            'usage' => '用途',
        ];
    }

    /**
     * @throws \yii\web\UnprocessableEntityHttpException
     */
    public function send()
    {
        $code = rand(1000, 9999);
        return Yii::$app->services->lxsms->send($this->mobile, $code, $this->usage);
    }
}
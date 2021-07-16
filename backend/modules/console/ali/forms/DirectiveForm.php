<?php

namespace backend\modules\console\ali\forms;

use Yii;
use yii\base\Model;

/**
 * Class LoginForm
 * @package backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class DirectiveForm extends Model
{
    public $value;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['value'], 'required'],
            [['value'], 'double']
        ];
    }

    public function attributeLabels()
    {
        return [
            'value' 	=> '数值',
        ];
    }

}
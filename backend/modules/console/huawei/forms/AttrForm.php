<?php

namespace backend\modules\console\huawei\forms;

use Yii;
use yii\base\Model;

/**
 * Class ClearCache
 * @package backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class AttrForm extends Model
{
    /**
     * @var int
     */
    public $service = 1;


    public function rules()
    {
        return [
            [['service'], 'integer'],
        ];
    }
}
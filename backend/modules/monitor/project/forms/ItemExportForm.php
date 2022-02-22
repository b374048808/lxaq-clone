<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2022-02-14 09:38:11
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2022-02-14 10:20:24
 * @Description: 
 */

namespace backend\modules\monitor\project\forms;

use Yii;
use yii\base\Model;

/**
 * Class ClearCache
 * @package backend\forms
 * @author jianyan74 <751393839@qq.com>
 */
class ItemExportForm extends Model
{
    /**
     * @var int
     */
    public $from_date = '';
    public $to_date = '';

    /**
     * @var bool
     */
    protected $status = true;

    public function rules()
    {
        return [
            [['from_date', 'to_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'from_date' => '开始时间',
            'to_date' => '结束时间',
        ];
    }
}

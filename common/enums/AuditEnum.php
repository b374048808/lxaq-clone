<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-01 14:26:41
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-10-08 14:55:41
 * @Description: 
 */

namespace common\enums;

use yii\helpers\Html;

/**
 * Class AuditStateEnum
 * @package common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class AuditEnum extends BaseEnum
{
    const THROUGH = 2;
    const SUBMIT = 1;
    const DEFAULT = 0;
    const REJECT = -1;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::THROUGH => '通过',
            self::SUBMIT => '待审核',
            self::DEFAULT => '进行中',
            self::REJECT => '驳回',
        ];
    }

    /**
     * @return array
     */
    public static function audit(): array
    {
        return [
            self::THROUGH => '审核中',
            self::SUBMIT => '驳回',
        ];
    }


    public static function getWechatMap():array{
        return [
            [
                'text'  => '全部任务',
                'value' => ''
            ],
            [
                'text'  => '通过',
                'value' => self::THROUGH
            ],
            [
                'text'  => '已提交',
                'value' => self::SUBMIT
            ],
            [
                'text'  => '进行中',
                'value' => self::DEFAULT
            ],
            [
                'text'  => '驳回',
                'value' => self::REJECT
            ],
        ];
    }


    public static function getType($key){
        $html = [
            self::THROUGH => 'success',
            self::SUBMIT => 'warning',
            self::DEFAULT => 'primary',
            self::REJECT => 'danger ',
        ];
        return $html[$key] ?? '';
    }

    /**
     * @param $key
     * @return mixed|string
     */
    public static function html($key)
    {
        $html = [
            self::THROUGH => Html::tag('span', self::getValue(self::THROUGH), array_merge(
                [
                    'class' => "label label-success",
                ]
            )),
            self::SUBMIT => Html::tag('span', self::getValue(self::SUBMIT), array_merge(
                [
                    'class' => "label label-info",
                ]
            )),
            self::DEFAULT => Html::tag('span', self::getValue(self::DEFAULT), array_merge(
                [
                    'class' => "label label-default",
                ]
            )),
            self::REJECT => Html::tag('span', self::getValue(self::DEFAULT), array_merge(
                [
                    'class' => "label label-warning",
                ]
            )),
        ];

        return $html[$key] ?? '';
    }
}
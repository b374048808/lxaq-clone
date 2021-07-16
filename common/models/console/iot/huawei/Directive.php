<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-21 10:47:51
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 10:50:54
 * @Description: 
 */

namespace common\models\console\iot\huawei;

use common\helpers\ArrayHelper;
use common\enums\StatusEnum;

/**
 * This is the model class for table "rf_lx_iot_huawei_directive".
 *
 * @property int $id
 * @property int $pid 产品id
 * @property string $title 标题
 * @property string $content 指令
 * @property int $sort 优先级
 * @property int $status 状态[-1:删除;0:已使用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Directive extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_huawei_directive';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['title'], 'string', 'max' => 50],
            [['content'], 'string', 'max' => 140],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => 'Pid',
            'title' => '标题',
            'content' => '内容',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * 命令列表
     *
     * @return array
     */
    public static function getMap(){
        $model = self::find()
            ->where(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return ArrayHelper::map($model, 'id','title');
    }
}

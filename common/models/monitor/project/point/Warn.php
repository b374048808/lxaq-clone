<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-03-26 11:07:06
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 15:37:44
 * @Description: 
 */

namespace common\models\monitor\project\point;

use common\models\monitor\project\House;
use common\models\monitor\project\log\WarnLog;
use common\models\monitor\project\Point;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_warn".
 *
 * @property int $id
 * @property int $pid 监测点ID
 * @property int $value_id 数据ID
 * @property string $description 描述
 * @property int $warn 报警等级
 * @property int $state 处理方式
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Warn extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_warn';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'warn', 'state', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['description'], 'string', 'max' => 140],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pid' => '监测点',
            'description' => '描述',
            'warn' => '报警等级',
            'state' => '处理方式',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {

        // 修改数据记录数据修改日志
        if (!$this->isNewRecord) {
            $model = self::findOne($this->id);
            // 没有数据发生变动
            if($this->description == $model['description'] && $this->state == $model['state'] && $this->warn == $model['warn']){
                goto S; //钩子跳转
            }            
            // 添加修改日志
            $logModel = new WarnLog();
            $logModel->pid = $this->id;
            $logModel->remark = $this->description;
            $logModel->warn = $this->warn;
            $logModel->state = $this->state;
            $logModel->save();
        }
        S:
        return parent::beforeSave($insert);
    }

    public function getHouse()
    {
        return $this->hasOne(House::class,['id' => 'pid'])
            ->viaTable(Point::tableName(),['id' => 'pid']);
    }

    public function getPoint()
    {
        return $this->hasOne(Point::class,['id' => 'pid']);
    }
}

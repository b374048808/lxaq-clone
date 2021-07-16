<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-15 15:00:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-30 14:35:39
 * @Description: 
 */

namespace common\models\monitor\project\point;

use common\models\monitor\project\Point;
use common\models\monitor\project\House;
use common\models\monitor\project\log\ValueLog;
use common\enums\StatusEnum;
use common\enums\ValueStateEnum;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_value".
 *
 * @property int $id
 * @property int $pid 监测点id
 * @property int $type 数据类型[1:动态,2:人工]
 * @property string $value 数据
 * @property string $initial_value 初始数据
 * @property int $event_time 时间
 * @property int $warn 预警等级
 * @property int $state 状态，0关闭，1开启，2审核中
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 添加时间
 * @property int $updated_at 修改时间
 */
class Value extends \common\models\base\BaseModel
{

    public $remark;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_value';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid'], 'required'],
            [['pid', 'type', 'warn', 'state', 'status', 'created_at', 'updated_at'], 'integer'],
            [['value', 'initial_value'], 'number'],
            ['event_time','safe']
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
            'type' => '类型',
            'value' => '数据',
            'initial_value' => '初始值',
            'event_time' => '时间',
            'warn' => '报警等级',
            'state' => '审核',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '修改时间',
        ];
    }


    public function getParent()
    {
        return $this->hasOne(Point::class,['id' => 'pid']);
    }


    public function getHouse()
    {
        return $this->hasOne(House::class,['id' => 'pid'])
            ->viaTable(Point::tableName(),['id' => 'pid']);
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        // 修改数据记录数据修改日志
        if (!$this->isNewRecord) {
            // 添加修改日志
            $logModel = new ValueLog();
            $logModel->pid = $this->id;
            $logModel->type = $this->type;
            $logModel->value = $this->value;
            $logModel->event_time = $this->event_time;
            $logModel->warn = $this->warn;
            $logModel->state = $this->state;
            $logModel->save();
        }

        return parent::beforeSave($insert);
    }

    
    public static function getPrevValue($id)
    {
         $toModel = self::findOne($id);
         $model = self::find()
            ->where(['pid' => $toModel['pid']])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andWhere(['state' => ValueStateEnum::ENABLED])
            ->andWhere([
                '<',
                'event_time',
                $toModel['event_time']
            ])
            ->orderBy('event_time DESC')
            ->asArray()
            ->one();
        return $model['value'];
    }

    /**
     * @param  number pid
     * @param  array data
     * @return number
     * @throws: 
     */
    static public function addDatas($pid, $data)
    {
        $i = 0;
        // 删除原有标签关联;
        if (!empty($data)) {
            $model = new self;
            $res = [];
            foreach ($data as $key => $value) {
                $res['event_time'] = strtotime($value[0]);
                $res['value'] = $value[1];
                $res['pid'] = $pid;
                $_model = clone $model;
                if ($_model->load($res,'') && $_model->save()) {
                    $i++;
                }
            }
            // 批量插入数据
            // Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();
        }
        return $i;
    }
}

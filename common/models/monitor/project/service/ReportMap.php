<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-09-16 10:53:50
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-11-01 17:44:13
 * @Description: 
 */

namespace common\models\monitor\project\service;

use common\enums\StatusEnum;
use Yii;
use common\helpers\ArrayHelper;
use common\models\monitor\project\house\Report;

/**
 * This is the model class for table "rf_lx_monitor_item_house_map".
 *
 * @property int $service_id 项目id
 * @property int $report_id 建筑物id
 */
class ReportMap extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_service_report_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['service_id', 'report_id'], 'required'],
            [['service_id', 'report_id'], 'integer'],
            [['service_id', 'report_id'], 'unique', 'targetAttribute' => ['service_id', 'report_id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'service_id' => 'Item ID',
            'report_id' => 'House ID',
        ];
    }

    public function getReport(){
        return $this->hasOne(Report::class,['id' => 'report_id'])->andWhere(['status' => StatusEnum::ENABLED]);
    }

    public static function getReportIds($id){
        
        $model = self::find()
            ->where(['service_id' => $id])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($model,'report_id', $keepKeys = true);
    }

    /**
     * @param $service_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addMap($service_id, $ids=[])
    {
        // 删除原有标签关联;
        if ($service_id && !empty($ids)) {
            $data = [];

            foreach ($ids as $v) {
                $data[] = [$v, $service_id];
            }

            $field = ['report_id', 'service_id'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

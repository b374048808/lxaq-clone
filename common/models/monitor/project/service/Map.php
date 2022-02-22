<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-10-18 14:21:01
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-12-08 10:12:27
 * @Description: 
 */

namespace common\models\monitor\project\service;

use common\enums\VerifyEnum;
use Yii;
use common\models\monitor\project\House;
use common\helpers\ArrayHelper;
use common\models\monitor\project\house\Report;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "rf_lx_monitor_service_map".
 *
 * @property int $id
 * @property int $pid 派单
 * @property int $map_id 房屋
 * @property string $description 描述
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 * @property array $images 照片
 * @property array $files 附件
 */
class Map extends \common\models\base\BaseModel
{

    public $report;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_service_map';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    { 
        return [
            [['pid', 'map_id'], 'required'],
            [['map_id'], 'unique', 'filter' => function(ActiveQuery $query) {
                return $query->andWhere(['pid' => $this->pid]);
            },'message' => '此房屋已添加'],
            [['pid', 'map_id', 'status', 'created_at', 'updated_at', 'user_id'], 'integer'],
            [['images', 'files', 'report'], 'safe'],
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
            'pid' => '任务',
            'map_id' => '房屋',
            'description' => '说明',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
            'images' => '附件图片',
            'files' => '附件',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        if ($this->report) {
            $db = Yii::$app->db;
            // 在主库上启动事务
            $transaction = $db->beginTransaction();

            try {
                ReportMap::deleteAll(['service_id' => $this->id]);
                $ids = [];
                foreach ($this->report as $key => $value) {
                    $model = $value['id'] ? Report::findOne($value['id']) : new Report();
                    $model->load($value, '');
                    $model->pid = $this->map_id;
                    $model->verify = $model->isNewRecord?VerifyEnum::WAIT:$model->verify;
                    $model->user_id = $this->user_id;
                    if ($model->save()) {
                        array_push($ids, $model->attributes['id']);
                    }
                }
                ReportMap::addMap($this->id, $ids);
                $transaction->commit();
            } catch (\Exception $e) {
                $transaction->rollBack();
                throw $e;
            } catch (\Throwable $e) {
                $transaction->rollBack();
                throw $e;
            }
            # code...
        }
        return parent::beforeSave($insert);
    }

    /**
     * dsad
     * @param {*}
     * @return {*}
     * @throws: 
     */    
    public function getReport()
    {
        return $this->hasMany(Report::class, ['pid' => 'id'])
            ->viaTable(House::tableName(),['id' => 'map_id'])->andWhere(['>=','verify',VerifyEnum::WAIT]);
    }

    public function getHouse()
    {
        return $this->hasOne(House::class, ['id' => 'map_id']);
    }

    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'pid']);
    }

    public static function getReportIds($id)
    {
        $model = self::find()
            ->where(['pid' => $id])
            ->asArray()
            ->all();
        $ids = ArrayHelper::getColumn($model,'id', $keepKeys = true);
        $reportModel = ReportMap::find()
            ->where(['in','service_id',$ids])
            ->asArray()
            ->all();
        return ArrayHelper::getColumn($reportModel,'report_id', $keepKeys = true);

    }


    public static function getHouseIds($id)
    {
        $model = self::find()
            ->where(['pid' => $id])
            ->asArray()
            ->all();

        return ArrayHelper::getColumn($model, 'map_id', $keepKeys = true);
    }


    /**
     * @param $item_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addHouses($item_id, $houses)
    {
        // 删除原有标签关联;
        if ($item_id && !empty($houses)) {
            $data = [];

            foreach ($houses as $v) {
                $data[] = [$v, $item_id];
            }

            $field = ['map_id', 'pid'];
            // 批量插入数据
            Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

            return true;
        }

        return false;
    }
}

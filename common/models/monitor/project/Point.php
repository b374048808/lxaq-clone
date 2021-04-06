<?php

namespace common\models\monitor\project;

use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point".
 *
 * @property int $id
 * @property int $pid 房屋ID
 * @property string $title 名称
 * @property int $type 监测类型
 * @property string $location 位置
 * @property array $covers 照片
 * @property array $warn 预警值
 * @property double $lng 经度
 * @property double $lat 纬度
 * @property string $initial_value 初始数据
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Point extends \common\models\base\BaseModel
{

    public $lnglat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['pid', 'title'], 'required'],
            [['pid', 'type', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['covers', 'warn','lnglat'], 'safe'],
            [['lng', 'lat', 'initial_value'], 'number'],
            [['title'], 'string', 'max' => 50],
            [['location'], 'string', 'max' => 100],
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
            'pid' => '房屋',
            'title' => '标题',
            'type' => '类型',
            'location' => '位置',
            'covers' => '图像',
            'warn' => '报警',
            'lng' => 'Lng',
            'lat' => 'Lat',
            'initial_value' => '初始数据',
            'description' => '备注',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }


    public static function getType($id){
        $model = self::findOne($id);
        return $model->type;
    }
}

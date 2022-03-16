<?php

namespace common\models\monitor\project;

use Yii;
use common\enums\StatusEnum;
use common\helpers\ArrayHelper;
use common\models\monitor\project\point\HuaweiMap;
use common\models\monitor\project\point\Value;
use common\models\monitor\project\rule\Item;
use common\models\worker\Worker;

/**
 * This is the model class for table "rf_lx_monitor_house".
 *
 * @property int $id
 * @property int $entry_id 录入人员id
 * @property string $title 户主（单位信息）
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property string $cover 封面
 * @property int $item_id 项目ID
 * @property string $mobile 手机号码
 * @property string $address 默认地址
 * @property string $owner 业主单位
 * @property string $design 设计单位
 * @property string $supervision 监理单位
 * @property string $prospect 地质勘查单位
 * @property string $roadwork 施工单位
 * @property int $year 建筑年份
 * @property string $area 面积
 * @property int $nature 房屋性质
 * @property int $layer 结构层数
 * @property int $news 房屋朝向
 * @property int $type 结构类型
 * @property int $roof 屋面形式
 * @property double $lng 经度
 * @property double $lat 纬度
 * @property array $hint_cover 示意图
 * @property array $layout_cover 建筑物图
 * @property array $plan_cover 平面图
 * @property int $floor 楼板形式
 * @property int $wall 墙体形式
 * @property int $basement 地下室
 * @property int $beam 圈梁
 * @property int $column 构造柱
 * @property array $angle_warn 倾斜预警
 * @property array $settling_warn 沉降预警
 * @property array $move_warn 平顶位移预警
 * @property array $cracks_warn 裂缝预警
 * @property string $height 建筑物高度
 * @property string $width 建筑物宽度
 * @property string $length 建筑物长度
 * @property array $angle_cover 倾斜监测点示意图
 * @property array $move_cover 位移监测点示意图
 * @property array $cracks_cover 裂缝监测点示意图
 * @property array $settling_cover 沉降监测点示意图
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property string $side 周边环境
 * @property string $property_nature 产权性质
 * @property int $room 间   数
 * @property string $base_form 基础形式
 * @property string $use_change 用途变更
 * @property string $disasters 灾  害
 * @property string $detect_scope 鉴定范围
 * @property string $property_card 产权证号
 * @property string $land_card 地号
 */
class House extends \common\models\base\BaseModel
{

    public $lnglat;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_house';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['title', 'required'],
            [['entry_id', 'room', 'province_id', 'city_id', 'area_id', 'item_id', 'year', 'nature', 'layer', 'floor', 'wall', 'basement', 'beam', 'column', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['area', 'lng', 'lat', 'height', 'width', 'length'], 'number'],
            [['hint_cover', 'layout_cover', 'plan_cover', 'angle_warn', 'settling_warn', 'move_warn', 'cracks_warn', 'angle_cover', 'move_cover', 'cracks_cover', 'settling_cover'], 'safe'],
            [['title', 'owner', 'design', 'supervision', 'prospect', 'roadwork', 'side', 'property_nature', 'base_form', 'use_change', 'disasters', 'detect_scope', 'property_card', 'land_card', 'roof', 'type'], 'string', 'max' => 50],
            [['cover', 'address'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 20],
            [['news'], 'string', 'max' => 10],
            [['description', 'history'], 'string', 'max' => 140],
            ['lnglat', 'safe']
        ];
    }

    /** 
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'entry_id' => '录入人员ID',
            'title' => '姓名(单位名称)',
            'province_id' => '省',
            'city_id' => '城市',
            'area_id' => '镇',
            'cover' => '封面',
            'mobile' => '联系方式',
            'address' => '详细地址',
            'owner' => '业主单位',
            'design' => '设计单位',
            'supervision' => '监理单位',
            'prospect' => '地质勘查单位',
            'roadwork' => '施工单位',
            'year' => '年份',
            'area' => '面积',
            'nature' => '性质',
            'layer' => '层数',
            'news' => '朝向',
            'type' => '结构类型',
            'roof' => '屋面形式',
            'history'   => '历史情况',
            'lng' => '经度',
            'lat' => 'L纬度',
            'hint_cover' => '示意图',
            'layout_cover' => '建筑物图',
            'plan_cover' => '片面图',
            'floor' => '楼板形式',
            'wall' => '墙体',
            'basement' => '地下室',
            'beam' => '圈梁',
            'column' => '构造柱',
            'angle_warn' => '倾斜预警',
            'settling_warn' => 'Settling Warn',
            'move_warn' => 'Move Warn',
            'cracks_warn' => 'Cracks Warn',
            'height' => '建筑物高度',
            'width' => '建筑物侧面（宽度）',
            'length' => '建筑物正面（长度）',
            'angle_cover' => '倾斜点位示意图',
            'move_cover' => '平顶位移点位示意图',
            'cracks_cover' => '裂缝点位示意图',
            'settling_cover' => '沉降点位示意图',
            'description' => '描述',
            'sort' => '优先级',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
            'side' => '周边环境',
            'property_nature' => '产权性质',
            'room' => '间   数',
            'base_form' => '基础形式',
            'use_change' => '用途变更',
            'disasters' => '灾  害',
            'detect_scope' => '鉴定范围',
            'property_card' => '产权证号',
            'land_card' => '地号',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->entry_id = $this->entry_id ?: Yii::$app->user->id;
        }
        if (!empty($this->lnglat)) {

            // $lnglat =Map::bd_decrypt($this->lnglat['lng'],$this->lnglat['lat']);
            $this->lng = $this->lnglat['lng'];
            $this->lat = $this->lnglat['lat'];
        }

        return parent::beforeSave($insert);
    }

    public function getSimple()
    {
        return $this->hasMany(Item::class, ['pid' => 'id'])
            ->andWhere(['status' => StatusEnum::ENABLED]);
    }

    public function getWarn()
    {
        return $this->hasOne(Point::class, ['pid' => 'id'])
            ->select(['warn', 'pid'])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->orderBy('warn desc');
    }

    public function getPoint()
    {
        return $this->hasMany(Point::class, ['pid' => 'id']);
    }


    public function getDeviceMap()
    {
        return $this->hasMany(HuaweiMap::class, ['point_id' => 'id'])
            ->viaTable(Point::tableName(), ['pid' => 'id']);
    }

    /**
     * @param {*} $id
     * @return {*}
     * @throws:  返回房屋户主
     */
    public static function getTitle($id)
    {
        $model = self::find()
            ->where(['id' => $id])
            ->asArray()
            ->one();
        return $model['title'];
    }

    public function getMember()
    {
        return $this->hasOne(Worker::class, ['id' => 'entry_id']);
    }

    /**
     * 房屋下所有的监测点
     * 
     * @param n*o $id
     * @return array
     * @throws: 
     */
    public static function getPointColumn($id, $type = null)
    {
        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andFilterWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return $pointModel ? ArrayHelper::getColumn($pointModel, 'id', $keepKeys = true) : [];
    }

    /**
     * 房屋下所有的监测点列表
     * 
     * @param n*o $id
     * @return array
     * @throws: 
     */
    public static function getPointMap($id, $type = null)
    {
        $pointModel = Point::find()
            ->where(['pid' => $id])
            ->andFilterWhere(['type' => $type])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        return $pointModel ? ArrayHelper::map($pointModel, 'title', 'title') : [];
    }

    /**
     * @param $ground_id
     * @param $houses
     * @return bool
     * @throws \yii\db\Exception
     */
    static public function addDatas($data)
    {
        $i = 0;
        // 删除原有标签关联;
        if (!empty($data)) {
            $field = [
                'title',
                'province_id',
                'city_id',
                'area_id',
                'mobile',
                'address',
                'year',
                'area',
                'height',
                'width',
                'length',
                'description',
                'lng',
                'lat',
                'owner',
                'design',
                'supervision',
                'prospect',
                'roadwork',
            ];
            $model = new self;
            foreach ($data as $key => $value) {
                $array_ab = array_combine($field, $value);
                $_model = clone $model;
                if ($_model->load($array_ab, '') && $_model->save()) {
                    $i++;
                }
            }

            // 批量插入数据
            // Yii::$app->db->createCommand()->batchInsert(self::tableName(), $field, $data)->execute();

        }

        return $i;
    }
}

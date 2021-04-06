<?php

namespace common\models\monitor\project;

use Yii;

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
            [['entry_id', 'province_id', 'city_id', 'area_id', 'item_id', 'year', 'nature', 'layer', 'news', 'type', 'roof', 'floor', 'wall', 'basement', 'beam', 'column', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['area', 'lng', 'lat', 'height', 'width', 'length'], 'number'],
            [['hint_cover', 'layout_cover', 'plan_cover', 'angle_warn', 'settling_warn', 'move_warn', 'cracks_warn', 'angle_cover', 'move_cover', 'cracks_cover', 'settling_cover'], 'safe'],
            [['title', 'owner', 'design', 'supervision', 'prospect', 'roadwork'], 'string', 'max' => 50],
            [['cover', 'address'], 'string', 'max' => 100],
            [['mobile'], 'string', 'max' => 20],
            [['description'], 'string', 'max' => 140],
            ['lnglat','safe']
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
            'title' => '标题',
            'province_id' => '省',
            'city_id' => '城市',
            'area_id' => '镇',
            'cover' => '封面',
            'item_id' => '项目ID',
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
        ];
    }
}

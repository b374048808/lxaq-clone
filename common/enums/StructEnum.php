<?php

namespace common\enums;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class StructEnum extends BaseEnum
{
    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            
        ];
    }

    public static $natureList = [
        '住宅','商业用房','办公用房','教育用房','医院用房','体育用房',
		'其他公共类建筑','工业用房','非住宅营业房','住宅出租房','其他',
	];
	
	public static $typeList = [
		'未选择',
		'钢结构',
		'钢、钢筋混凝土结构',
		'钢筋混凝土结构',
		'混合结构',
		'砖木结构',
		'木结构',
		'其他结构',
	];
	
	public static $roofList = [
		'未选择',
		'木屋盖',
		'预制多孔板',
		'钢筋混凝土现浇板',
		'小梁小板',
		'石板',
		'平屋顶',
		'坡屋顶'
	];

}
<?php

namespace common\enums;

use common\models\monitor\project\Point;
use common\models\monitor\project\point\Angle;

/**
 * Class LogisticsTypeEnum
 * @package addons\TinyShop\common\enums
 * @author jianyan74 <751393839@qq.com>
 */
class PointEnum extends BaseEnum
{
    const ANGLE = 1;
    const CRACKS = 2;
    const SINK = 3;
    const MOVE = 4;

    /**
     * @return array
     */
    public static function getMap(): array
    {
        return [
            self::ANGLE => '倾斜',
            self::CRACKS => '裂缝',
            self::SINK => '沉降',
            self::MOVE => '平顶位移',
        ];
    }


    public static function getModel($id){
        $pointModel = Point::findOne($id);
        switch ($pointModel->type) {
            case self::ANGLE:
                return Angle::class;
                break;
            
            default:
                # code...
                break;
        }
    }
}
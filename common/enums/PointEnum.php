<?php

namespace common\enums;

use common\models\monitor\project\log\AngleLog;
use common\models\monitor\project\log\SinksLog;
use common\models\monitor\project\log\MoveLog;
use common\models\monitor\project\log\CracksLog;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Angle;
use common\models\monitor\project\point\Cracks;
use common\models\monitor\project\point\Move;
use common\models\monitor\project\point\Sink;

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

    /**
     * @return array
     */
    public static function getSymbolMap(): array
    {
        return [
            self::ANGLE => 'angle',
            self::CRACKS => 'cracks',
            self::SINK => 'sink',
            self::MOVE => 'move',
        ];
    }

    /**
     * @return array
     */
    public static $Until=[
        self::ANGLE => '%',
        self::CRACKS => 'mm',
        self::SINK => 'mm',
        self::MOVE => 'mm',
    ];

    /**
     * @return array
     */
    public static function getAlert($id){
        $data = [
            self::ANGLE => '需要通过连续两个月倾斜率对比来判断房屋是否倾斜严重',
        ];
        return $data[$id];
    }

    /**
     * @return array
     */
    public static function getSymbolValue($id)
    {
        return static::getSymbolMap()[$id]?:'';
    }

     /**
     * @return array
     */
    public static function getCoverMap(): array
    {
        return [
            self::ANGLE => 'angle_cover',
            self::CRACKS => 'cracks_cover',
            self::SINK => 'sink_cover',
            self::MOVE => 'move_cover',
        ];
    }


    public static function getCover($id)
    {
        return static::getCoverMap()[$id]?:'';
    }


    public static function getModel($type)
    {
        switch ($type) {
            case self::ANGLE:
                return Angle::class;
                break;
            case self::CRACKS:
                return Cracks::class;
                break;
            case self::SINK:
                return Sink::class;
                break;
            case self::MOVE:
                return Move::class;
                break;
            default:
                # code...
                break;
        }
    }

    public static function getLogModel($type)
    {
        switch ($type) {
            case self::ANGLE:
                return AngleLog::class;
                break;
            case self::CRACKS:
                return CracksLog::class;
                break;
            case self::SINK:
                return SinksLog::class;
                break;
            case self::MOVE:
                return MoveLog::class;
                break;
            default:
                # code...
                break;
        }
    }
}

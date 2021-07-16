<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-05-27 16:06:04
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-05-27 16:45:34
 * @Description: 监测点服务
 */

namespace services\monitor;

use Yii;
use common\enums\StatusEnum;
use common\components\Service;
use common\models\monitor\project\point\AliMap;
use common\models\monitor\project\point\HuaweiMap;

/**
 * Class PointService
 * @package services\monitor
 * @author 
 */
class PointService extends Service
{

    /**
     * 统计各类型设备安装记录
     * 
     * 
     * @param {*} $id
     * @param {*} $start_time
     * @param {*} $end_time
     * @return {*}
     * @throws: 
     */    
    public function getDeviceMap($id, $start_time = null, $end_time = null)
    {
        $res = [];
        return array_merge($this->getHuaweiMap($id,$start_time,$end_time), $this->getAliMap($id,$start_time,$end_time));
    }

    /**
     * 华为设备安装合计
     * 
     * @param {*} $id
     * @param {*} $start_time
     * @param {*} $end_time
     * @return {*}
     * @throws: 
     */    
    public function getHuaweiMap($id, $start_time = null, $end_time = null)
    {
        $model = HuaweiMap::find()
            ->with('device')
            ->where(['point_id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['between', 'install_time', $start_time, $end_time])
            ->asArray()
            ->all();
        $model = $this->findToArray($model);
        return $model;
    }


    /**
     * 阿里设备安装合计
     * 
     * @param {*} $id
     * @param {*} $start_time
     * @param {*} $end_time
     * @return {*}
     * @throws: 
     */    
    public function getAliMap($id, $start_time = null, $end_time = null)
    {
        $model = AliMap::find()
        ->with('device')
            ->where(['point_id' => $id])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->andFilterWhere(['between', 'install_time', $start_time, $end_time])
            ->asArray()
            ->all();
            $model = $this->findToArray($model);
        return $model;
    }

    /**
     * 多级数组转为单数组
     * 
     * @param array
     * @return array
     * @throws: 
     */    
    public function findToArray($data)
    {
        foreach ($data as $key => &$value) {
            $value = array_merge($value,$value['device']);
            unset($value['device']);
            # code...
        }
        return $data;
    }
}

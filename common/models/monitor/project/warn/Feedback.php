<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-06-29 08:32:38
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-06-29 09:19:29
 * @Description: 
 */

namespace common\models\monitor\project\warn;

use common\models\backend\Member;
use common\models\monitor\project\Point;
use common\models\monitor\project\point\Warn;
use Yii;

/**
 * This is the model class for table "rf_lx_monitor_point_warn_feedback".
 *
 * @property int $id
 * @property int $user_id 管理员id
 * @property int $pid 报警
 * @property array $files 上传的内容
 * @property string $description 描述
 * @property int $sort 优先级
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 */
class Feedback extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_monitor_point_warn_feedback';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'pid', 'sort', 'status', 'created_at', 'updated_at'], 'integer'],
            [['files'], 'safe'],
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
            'user_id' => '人员',
            'pid' => 'Pid',
            'files' => '附件',
            'description' => '描述',
            'sort' => '排序',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '更新时间',
        ];
    }

    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
        $this->user_id = Yii::$app->user->id;

        return parent::beforeSave($insert);
    }


    public function getUser()
    {
        return $this->hasOne(Member::class,['id' => 'user_id']);
    }

    public function getWarn()
    {
        return $this->hasOne(Warn::class,['id' => 'pid']);

    }


    public function getPoint()
    {
        return $this->hasOne(Point::class,['id' => 'pid'])
            ->viaTable(Warn::tableName(),['id' => 'pid']);
    }

}

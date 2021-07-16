<?php

namespace common\models\console\iot\ali;

use Yii;
use common\models\member\Member;
/**
 * This is the model class for table "rf_lx_iot_ali_directive_log".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property int $device_id 设备id
 * @property int $directive_id 指令id
 * @property string $content 指令
 * @property array $results 设备返回结果
 * @property string $ip ip地址
 * @property int $status 状态[-1:删除;0:已使用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 更新时间
 * @property array $params 参数
 */
class DirectiveLog extends \common\models\base\BaseModel
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_lx_iot_ali_directive_log';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['user_id', 'device_id', 'directive_id', 'status', 'created_at', 'updated_at'], 'integer'],
            [['results', 'params'], 'safe'],
            [['content'], 'string', 'max' => 250],
            [['ip'], 'string', 'max' => 30],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'device_id' => 'Device ID',
            'directive_id' => 'Directive ID',
            'content' => '内容',
            'results' => '结果',
            'ip' => 'Ip',
            'status' => '状态',
            'created_at' => '添加时间',
            'updated_at' => '更新时间',
            'params' => '参数',
        ];
    }

    /**
     * 关联设备
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDevice()
    {
        return $this->hasOne(Device::class,['id' => 'device_id']);
    }

    /**
     * 关联命令
     *
     * @return \yii\db\ActiveQuery
     */
    public function getDirective()
    {
        return $this->hasOne(Directive::class,['id' => 'directive_id']);
    }

    /**
     * 关联用户
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(Member::class, ['id' => 'user_id']);
    }


    /**
     * @param bool $insert
     * @return bool
     */
    public function beforeSave($insert)
    {
            $this->user_id = Yii::$app->user->identity->id;

        return parent::beforeSave($insert);
    }
}

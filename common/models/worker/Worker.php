<?php

namespace common\models\worker;

use common\helpers\StringHelper;
use Yii;
use yii\behaviors\BlameableBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use common\helpers\HashidsHelper;
use common\enums\StatusEnum;
use common\enums\AppEnum;
use common\models\base\User;
use common\helpers\RegularHelper;
use common\traits\Tree;
use common\models\rbac\AuthAssignment;
use common\models\rbac\AuthRole;
use common\helpers\ArrayHelper;
use yii\rbac\Role;

/**
 * This is the model class for table "rf_worker".
 *
 * @property int $id 主键
 * @property string $username 帐号
 * @property string $password_hash 密码
 * @property string $auth_key 授权令牌
 * @property string $password_reset_token 密码重置令牌
 * @property int $type 类别[1:普通员工;10管理员]
 * @property string $nickname 昵称
 * @property string $realname 真实姓名
 * @property string $head_portrait 头像
 * @property int $current_level 当前级别
 * @property int $gender 性别[0:未知;1:男;2:女]
 * @property string $qq qq
 * @property string $email 邮箱
 * @property string $birthday 生日
 * @property int $visit_count 访问次数
 * @property string $home_phone 家庭号码
 * @property string $mobile 手机号码
 * @property int $role 权限
 * @property int $last_time 最后一次登录时间
 * @property string $last_ip 最后一次登录ip
 * @property int $province_id 省
 * @property int $city_id 城市
 * @property int $area_id 地区
 * @property int $pid 上级id
 * @property int $level 级别
 * @property string $tree 树
 * @property int $status 状态[-1:删除;0:禁用;1启用]
 * @property int $created_at 创建时间
 * @property int $updated_at 修改时间
 */
class Worker extends User
{
    use Tree;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'rf_worker';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password_hash'], 'required', 'on' => ['backendCreate']],
            [['password_hash'], 'string', 'min' => 6, 'on' => ['backendCreate']],
            [['realname'], 'string', 'max' => 50,'on' => ['backendCreate']],
            [['username'], 'unique', 'filter' => function (ActiveQuery $query) {
                return $query->andWhere(['>=', 'status', StatusEnum::DISABLED]);
            }, 'on' => ['backendCreate']],
            [['username', 'mobile'], 'string', 'max' => 20, 'on' => ['backendCreate']],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '不是一个有效的手机号码', 'on' => ['backendCreate']],
            [['mobile'], 'unique', 'filter' => function (ActiveQuery $query) {
                return $query
                    ->andWhere(['>=', 'status', StatusEnum::DISABLED]);
            }, 'on' => ['backendCreate']],
            [['id', 'current_level', 'dept_id', 'level', 'type', 'gender', 'visit_count', 'role', 'last_time', 'province_id', 'city_id', 'area_id', 'pid', 'status', 'created_at', 'updated_at'], 'integer'],
            [['birthday'], 'safe'],
            [['username', 'qq', 'home_phone', 'mobile'], 'string', 'max' => 20],
            [['password_hash', 'password_reset_token', 'head_portrait'], 'string', 'max' => 150],
            [['auth_key'], 'string', 'max' => 32],
            [['nickname', 'realname'], 'string', 'max' => 50],
            [['email'], 'string', 'max' => 60],
            [['tree'], 'string', 'max' => 2000],
            [['last_ip'], 'string', 'max' => 16],
            ['mobile', 'match', 'pattern' => RegularHelper::mobile(), 'message' => '不是一个有效的手机号码'],
            [['mobile'], 'unique', 'filter' => function (ActiveQuery $query) {
                return $query
                    ->andWhere(['>=', 'status', StatusEnum::DISABLED]);
            }],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '账号',
            'password_hash' => '密码',
            'auth_key' => '授权登录key',
            'password_reset_token' => '密码重置token',
            'type' => '类型',
            'nickname' => '昵称',
            'realname' => '真实姓名',
            'dept_id'   => '部门',
            'head_portrait' => '头像',
            'current_level' => '当前级别',
            'gender' => '性别',
            'qq' => 'QQ',
            'email' => '邮箱',
            'birthday' => '生日',
            'visit_count' => '登录总次数',
            'home_phone' => '家庭号码',
            'mobile' => '手机号码',
            'role' => '权限',
            'last_time' => '最后一次登录时间',
            'last_ip' => '最后一次登录ip',
            'province_id' => 'Province ID',
            'city_id' => 'City ID',
            'area_id' => 'Area ID',
            'pid' => '上级id',
            'level' => '级别',
            'tree' => '树',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '修改时间',
        ];
    }

    /**
     * 场景
     *
     * @return array
     */
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios['backendCreate'] = ['username', 'password_hash','realname','mobile'];

        return $scenarios;
    }

    /**
     * 关联授权角色
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAssignment()
    {
        return $this->hasOne(AuthAssignment::class, ['user_id' => 'id'])
            ->where(['app_id' => AppEnum::WORKER]);
    }

    public function getRole()
    {
        return $this->hasOne(AuthRole::class, ['id' => 'role_id'])
            ->viaTable(AuthAssignment::tableName(), ['user_id' => 'id'])
            ->where(['app_id' => AppEnum::WORKER]);
    }


    /**
     * 关联级别
     */
    public function getWorkerLevel()
    {
        return $this->hasOne(Level::class, ['level' => 'current_level']);
    }

    /**
     * 关联第三方绑定
     */
    public function getAuth()
    {
        return $this->hasMany(Auth::class, ['worker_id' => 'id'])->where(['status' => StatusEnum::ENABLED]);
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws \yii\base\Exception
     */
    public function beforeSave($insert)
    {
        if ($this->isNewRecord) {
            $this->last_ip = Yii::$app->request->getUserIP();
            $this->last_time = time();
            $this->auth_key = Yii::$app->security->generateRandomString();
        }


        return parent::beforeSave($insert);
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        if ($insert) {
            if (empty($this->nickname) && !empty($this->mobile)) {
                $nickname = StringHelper::random(5) . '_' . substr($this->mobile, -4);
                $this->nickname = $nickname;
                Worker::updateAll(['nickname' => $nickname], ['id' => $this->id]);
            }
        }

        parent::afterSave($insert, $changedAttributes);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $merchant_id = Yii::$app->services->merchant->getId();

        return [
            [
                'class' => TimestampBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => ['created_at', 'updated_at'],
                    ActiveRecord::EVENT_BEFORE_UPDATE => ['updated_at'],
                ],
            ],
            [
                'class' => BlameableBehavior::class,
                'attributes' => [
                    ActiveRecord::EVENT_BEFORE_INSERT => [],
                ],
            ]
        ];
    }

    public static function getRealname($id){
        $model = self::findOne($id);
        return $model['realname'];
    }

    public static function getMap($role = false)
    {
        $workers = Worker::find()
            ->with('role')
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();

        return ArrayHelper::map($workers, 'id', 'realname');
    }
}

<?php

namespace backend\modules\company\worker\forms;

use Yii;
use yii\base\Model;
use yii\web\NotFoundHttpException;
use common\models\rbac\AuthRole;
use common\models\worker\Worker;
use common\enums\AppEnum;

/**
 * Class WorkerForm
 * @package backend\modules\base\models
 * @author jianyan74 <751393839@qq.com>
 */
class WorkerForm extends Model
{
    public $id;
    public $password;
    public $username;
    public $role_id;

    /**
     * @var \common\models\backend\Worker
     */
    protected $worker;

    /*
     * @var \common\models\backend\AuthItem
     */
    protected $authItemModel;

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['password', 'username'], 'required'],
            ['password', 'string', 'min' => 6],
            [
                ['role_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AuthRole::class,
                'targetAttribute' => ['role_id' => 'id'],
            ],
            [['username'], 'isUnique'],
            [['role_id'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'password' => '登录密码',
            'username' => '登录名',
            'role_id' => '角色',
        ];
    }

    /**
     * 加载默认数据
     */
    public function loadData()
    {
        if ($this->worker = Yii::$app->services->backendWorker->findByIdWithAssignment($this->id)) {
            $this->username = $this->worker->username;
            $this->password = $this->worker->password_hash;
        } else {
            $this->worker = new Worker();
        }

        $this->role_id = $this->worker->assignment->role_id ?? '';
    }

    /**
     * 场景
     *
     * @return array
     */
    public function scenarios()
    {
        return [
            'default' => ['username', 'password'],
            'generalAdmin' => array_keys($this->attributeLabels()),
        ];
    }

    /**
     * 验证用户名称
     */
    public function isUnique()
    {
        $worker = Worker::findOne(['username' => $this->username]);
        if ($worker && $worker->id != $this->id) {
            $this->addError('username', '用户名称已经被占用');
        }
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();
        $worker = $this->worker;
        if ($worker->isNewRecord) {
            $worker->last_ip = '0.0.0.0';
            $worker->last_time = time();
        }
        $worker->username = $this->username;

        // 验证密码是否修改
        if ($this->worker->password_hash != $this->password) {
            $worker->password_hash = Yii::$app->security->generatePasswordHash($this->password);
        }

        if (!$worker->save()) {
            
            $this->addErrors($worker->getErrors());
            throw new NotFoundHttpException('用户编辑错误');
        }
        try {
            $worker = $this->worker;
            if ($worker->isNewRecord) {
                $worker->last_ip = '0.0.0.0';
                $worker->last_time = time();
            }
            $worker->username = $this->username;

            // 验证密码是否修改
            if ($this->worker->password_hash != $this->password) {
                $worker->password_hash = Yii::$app->security->generatePasswordHash($this->password);
            }

            if (!$worker->save()) {

                $this->addErrors($worker->getErrors());
                throw new NotFoundHttpException('用户编辑错误');
            }

            // 验证超级管理员
            if ($this->id == Yii::$app->params['adminAccount']) {
                $transaction->commit();

                return true;
            }
            
            // 角色授权
            Yii::$app->services->rbacAuthAssignment->assign([$this->role_id], $worker->id, AppEnum::WORKER);

            $transaction->commit();

            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();

            return false;
        }
    }
}
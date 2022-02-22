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
class WorkerRoleForm extends Model
{
    public $id;
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
            [
                ['role_id'],
                'exist',
                'skipOnError' => true,
                'targetClass' => AuthRole::class,
                'targetAttribute' => ['role_id' => 'id'],
            ],
            [['role_id'], 'required'],
        ];
    }

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return [
            'role_id' => '角色',
        ];
    }

    /**
     * 加载默认数据
     */
    public function loadData()
    {
        $this->worker = Yii::$app->services->backendWorker->findByIdWithAssignment($this->id)?:new Worker();
        

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
            'default' => ['role_id'],
            'roleAdmin' => array_keys($this->attributeLabels()),
        ];
    }


    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $worker = $this->worker;
            if ($worker->isNewRecord) {
                $worker->last_ip = '0.0.0.0';
                $worker->last_time = time();
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
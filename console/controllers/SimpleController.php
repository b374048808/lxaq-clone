<?php
/*
 * @Author: Xjie<374048808@qq.com>
 * @Date: 2021-04-25 14:55:20
 * @LastEditors: Xjie<374048808@qq.com>
 * @LastEditTime: 2021-07-14 11:19:24
 * @Description: 
 */

namespace console\controllers;

use common\enums\StatusEnum;
use Yii;
use yii\helpers\Console;
use yii\console\Controller;
use common\models\backend\Member;
use common\helpers\StringHelper;
use common\models\monitor\create\Simple;

/**
 * 随机生成数据规则
 *
 * Class SimpleController
 * @package console\controllers
 */
class SimpleController extends Controller
{

    public function actionIndex()
    {
        $model = Simple::find()
            ->with(['child'])
            ->where(['<=', 'start_time', time()])
            ->andwhere(['>=', 'end_time', time()])
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->asArray()
            ->all();
        foreach ($model as $key => $value) {
            echo $value['id'];
            Yii::$app->services->createSimple->setArrayValue($value['id']);
        }

        Console::stdout('生成成功！');

        exit();
    }

    /**
     * 初始化
     *
     * @throws \yii\base\Exception
     */
    public function actionInit()
    {
        if ($model = Member::findOne(1)) {
            $password_hash = StringHelper::random(10);
            $model->username = StringHelper::random(5);
            $model->password_hash = Yii::$app->security->generatePasswordHash($password_hash);

            if ($model->save()) {
                Console::output('username; ' . $model->username);
                Console::output('password; ' . $password_hash);
                exit();
            }

            Console::stdout('Password initialization failed');
            exit();
        }

        Console::stdout('Cannot find administrator');
        exit();
    }
}

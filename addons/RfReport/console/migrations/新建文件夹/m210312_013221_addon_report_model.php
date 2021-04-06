<?php

use yii\db\Migration;

class m210312_013221_addon_report_model extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_report_model}}', [
            `id` => "int(10) NOT NULL AUTO_INCREMENT",
            `cate_id` => "int(10) DEFAULT '0' COMMENT '模版类型'",
            `title` => "varchar(50) NOT NULL COMMENT '标题'",
            `file` => "varchar(100) NOT NULL COMMENT '模版地址'",
            `status` => "tinyint(4) DEFAULT '1' COMMENT '状态[-1:删除;0:已使用;1启用]'",
            `created_at` => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            `updated_at` => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='报告_模版'");

        /* 索引设置 */

        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_report_model}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

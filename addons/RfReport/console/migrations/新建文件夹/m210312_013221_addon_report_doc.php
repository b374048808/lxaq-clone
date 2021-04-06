<?php

use yii\db\Migration;

class m210312_013221_addon_report_doc extends Migration
{
    public function up()
    {
        /* 取消外键约束 */
        $this->execute('SET foreign_key_checks = 0');

        /* 创建表 */
        $this->createTable('{{%addon_report_doc}}', [
            `id` => "int(11) NOT NULL AUTO_INCREMENT COMMENT '主键'",
            `pid` => "int(10) DEFAULT '0' COMMENT '模版id'",
            `title` => "varchar(50) NOT NULL DEFAULT '' COMMENT '标题'",
            `file` => "varchar(100) NOT NULL COMMENT '生成地址'",
            `sort` => "int(5) DEFAULT '0' COMMENT '排序'",
            `status` => "tinyint(4) DEFAULT '1' COMMENT '状态'",
            `created_at` => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '创建时间'",
            `updated_at` => "int(10) unsigned NOT NULL DEFAULT '0' COMMENT '更新时间'",
            'PRIMARY KEY (`id`)'
        ], "ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COMMENT='扩展_报告生成记录'");

        /* 索引设置 */

        /* 表数据 */

        /* 设置外键约束 */
        $this->execute('SET foreign_key_checks = 1;');
    }

    public function down()
    {
        $this->execute('SET foreign_key_checks = 0');
        /* 删除表 */
        $this->dropTable('{{%addon_report_doc}}');
        $this->execute('SET foreign_key_checks = 1;');
    }
}

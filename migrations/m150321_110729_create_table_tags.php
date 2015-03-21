<?php

use yii\db\Schema;
use yii\db\Migration;

class m150321_110729_create_table_tags extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `ext_tagger_tags` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `label` varchar(160) DEFAULT NULL COMMENT 'Cimke',
                  `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                  `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                  `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                  `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                  PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";

        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('ext_tagger_tags');
    }
    
    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}

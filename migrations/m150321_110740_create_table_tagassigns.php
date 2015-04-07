<?php

use yii\db\Schema;
use yii\db\Migration;

class m150321_110740_create_table_tagassigns extends Migration
{
    public function up()
    {
        $sql = "CREATE TABLE IF NOT EXISTS `ext_tagger_assigns` (
                  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
                  `tag_id` int(11) unsigned NOT NULL COMMENT 'Cimke',
                  `model_class` varchar(160) NOT NULL DEFAULT '' COMMENT 'Osztály',
                  `model_id` int(11) NOT NULL COMMENT 'Példány',
                  `created_at` int(11) DEFAULT NULL COMMENT 'Létrehozva',
                  `created_user` int(11) DEFAULT NULL COMMENT 'Létrehozta',
                  `updated_at` int(11) DEFAULT NULL COMMENT 'Módosítva',
                  `updated_user` int(11) DEFAULT NULL COMMENT 'Módosította',
                  `status` varchar(1) DEFAULT NULL COMMENT 'Státusz',
                  PRIMARY KEY (`id`),
                  KEY `tag_id` (`tag_id`),
                  CONSTRAINT `ext_tagger_assigns_ibfk_1` FOREIGN KEY (`tag_id`) REFERENCES `ext_tagger_tags` (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $this->execute($sql);
    }

    public function down()
    {
        $this->dropTable('ext_tagger_assigns');
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

<?php

use yii\db\Schema;
use yii\db\Migration;

class m150321_110838_add_column_sort_order extends Migration
{
    public function up()
    {
        $this->addColumn('ext_tagger_tags', 'sort_order', 'smallint DEFAULT 1 AFTER id');
    }

    public function down()
    {
        $this->dropColumn('ext_tagger_tags', 'sort_order');
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

<?php

use yii\db\Migration;

class m170517_142939_create_tag_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';

        $this->createTable('{{%tag}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'slug' => $this->string()->notNull(),
        ], $tableOptions);

        $this->createIndex('{{%idx-tag-slug}}', '{{%tag}}', 'slug', true);
    }

    public function down()
    {
        $this->dropTable('{{%tag}}');
    }
}

<?php

use yii\db\Migration;

class m170847_203816_create_post_tag_table extends Migration
{
    public function up()
    {
        $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE=InnoDB';

        $this->createTable('{{%post_tag}}', [
            'post_id' => $this->integer()->notNull(),
            'tag_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->addPrimaryKey('{{%pk-post_tag}}', '{{%post_tag}}', ['post_id', 'tag_id']);

        $this->createIndex('{{%idx-post_tag-post_id}}', '{{%post_tag}}', 'post_id');
        $this->createIndex('{{%idx-post_tag-tag_id}}', '{{%post_tag}}', 'tag_id');

        $this->addForeignKey('{{%fk-post_tag-post_id}}', '{{%post_tag}}', 'post_id', '{{%post}}', 'id', 'CASCADE', 'RESTRICT');
        $this->addForeignKey('{{%fk-post_tag-tag_id}}', '{{%post_tag}}', 'tag_id', '{{%tag}}', 'id', 'CASCADE', 'RESTRICT');
    }

    public function down()
    {
        $this->dropTable('{{%post_tag}}');
    }
}

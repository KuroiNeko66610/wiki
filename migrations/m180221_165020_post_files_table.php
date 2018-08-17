<?php

use yii\db\Migration;

/**
 * Class m180221_165020_post_files_table
 */
class m180221_165020_post_files_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%post_files}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'filename' => $this->string(255)
        ]);

        $this->createIndex('idx-post_files-post_id', '{{%post_files}}', 'post_id');
        $this->addForeignKey('fk-post_files-user', '{{%post_files}}', 'post_id', '{{%post}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%post_files}}');
    }
}

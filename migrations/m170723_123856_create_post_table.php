<?php

use yii\db\Migration;

/**
 * Handles the creation of table `post`.
 */
class m170723_123856_create_post_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'category_id' => $this->integer(),
            'user_id' => $this->integer(),
            'title' => $this->string()->notNull(),
            'description' => $this->text()->notNull(),
            'status' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-post-user_id', '{{%post}}', 'user_id');

        $this->addForeignKey('fk-post-user', '{{%post}}', 'user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');

        $this->createIndex('idx-post-category_id', '{{%post}}', 'category_id');

        $this->addForeignKey('fk-post-category', '{{%post}}', 'category_id', '{{%category}}', 'id', 'SET NULL', 'CASCADE');

    }


    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%post}}');
    }
}

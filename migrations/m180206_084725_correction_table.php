<?php

use yii\db\Migration;

/**
 * Class m180206_084725_correction_table
 */
class m180206_084725_correction_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function safeUp()
    {
        $this->createTable('{{%correction}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(),
            'user_id' => $this->integer(),
            'title' => $this->string(255),
            'text' => $this->text()->notNull(),
            'reject_reason' => $this->text()->notNull(),
            'status' => $this->integer()->defaultValue(0),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ]);

        $this->createIndex('idx-correction-user_id', '{{%correction}}', 'user_id');
        $this->createIndex('idx-correction-post_id', '{{%correction}}', 'post_id');
        $this->createIndex('idx-correction-status', '{{%correction}}', 'status');


        $this->addForeignKey('fk-correction-user', '{{%correction}}', 'user_id', '{{%user}}', 'id', 'SET NULL', 'CASCADE');
        $this->addForeignKey('fk-correction-post', '{{%correction}}', 'post_id', '{{%post}}', 'id', 'CASCADE', 'CASCADE');

    }

    /**
     * @inheritdoc
     */
    public function safeDown()
    {
        $this->dropTable('{{%correction}}');

    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180206_084725_correction_table cannot be reverted.\n";

        return false;
    }
    */
}

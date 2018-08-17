<?php

use yii\db\Migration;

/**
 * Handles the creation of table `user_profile`.
 */
class m180221_073102_create_user_profile_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $this->createTable('{{%user_profile}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'favorites' => $this->text()
        ]);

        $this->createIndex('idx-user_profile-user_id', '{{%user_profile}}', 'user_id');
        $this->addForeignKey('fk-user_profile-user', '{{%user_profile}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%user_profile}}');
    }
}

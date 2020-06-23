<?php

use yii\db\Migration;

/**
 * Handles the creation of table `contacts`.
 */
class m200623_111732_create_contact_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('contacts', [
            'id'          => $this->primaryKey(),
            'user_id'     => $this->integer(),
            'name'        => $this->string(),
            'surname'     => $this->string(),
            'patronymic'  => $this->string()
        ]);

        $this->addForeignKey(
            'fk-contacts-user_id',
            'contacts',
            'user_id',
            'users',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-contacts-user_id', 'contacts');
        $this->dropTable('contacts');
    }
}

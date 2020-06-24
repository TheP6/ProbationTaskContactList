<?php

use yii\db\Migration;

/**
 * Handles the creation of table `phones`.
 */
class m200623_114042_create_phone_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('phones', [
            'id'         => $this->primaryKey(),
            'contact_id' => $this->integer(),
            'number'     => $this->string(),
            'label'      => $this->string(),
        ]);

        $this->addForeignKey(
            'fk-phones-contact_id',
            'phones',
            'contact_id',
            'contacts',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropForeignKey('fk-phones-contact_id', 'phones');
        $this->dropTable('phones');
    }
}

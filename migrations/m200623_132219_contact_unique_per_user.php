<?php

use yii\db\Migration;

/**
 * Class m200623_132219_contact_unique_per_user
 */
class m200623_132219_contact_unique_per_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'unique-contact-per-user',
            'contacts',
            [
                'user_id',
                'name',
                'surname',
                'patronymic'
            ],
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'unique-contact-per-user',
            'contacts'
        );
    }
}

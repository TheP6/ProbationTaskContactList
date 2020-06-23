<?php

use yii\db\Migration;

/**
 * Class m200623_132153_user_unique_login
 */
class m200623_132153_user_unique_login extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createIndex(
            'unique-login',
            'users',
            'login',
            true
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropIndex(
            'unique-login',
            'users'
        );
    }
}

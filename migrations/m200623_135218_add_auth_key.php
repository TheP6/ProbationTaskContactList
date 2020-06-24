<?php

use yii\db\Migration;

/**
 * Class m200623_135218_add_auth_key
 */
class m200623_135218_add_auth_key extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->addColumn('users', 'auth_key', $this->string());
        $this->addColumn('users', 'access_token', $this->string());
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropColumn('users', 'auth_key');
    }
}

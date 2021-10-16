<?php

use yii\db\Migration;

/**
 * Class m211015_092351_insert_user
 */
class m211015_092351_insert_user extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('news', [
            'username' => 'v2517v@gmail.com',
            'auth_key' => 'DRpN-ckR5Fz_mrAPcXUeVLPO7LJiXYt7',
            'password_hash' => '$2y$13$zvypea1gx5jMp9/4zd/SiuIJuZ5V3cfEUCf2NRtYQHZY3m3IqDmym',
            'email' => 'v2517v@gmail.com',
            'status' => '10',
            'verification_token' => 'f5kaVniOTDxAhskNslUYp2hz7luhWuJA_1634287402',
        ]);
        $this->insert('auth_item', [
            'name' => 'admin',
            'type' => '1',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211015_092351_insert_user cannot be reverted.\n";

        return false;
    }

}

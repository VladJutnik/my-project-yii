<?php

use yii\db\Migration;

/**
 * Class m211015_212241_create_user_admin
 */
class m211015_212241_create_user_admin extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('user', [
            'id' => '1',
            'username' => 'admin@gmail.com',
            'auth_key' => 'Gc2f3lEMoPzdAE1xvMbmFa_yQudgmR0Z', //пароль 123456789
            'password_hash' => '$2y$13$.EvBy.HzC/kOvnaT/1BXXuZTrEt/kX1FwYiev.evAZw1hHxdtDuuO',
            'email' => 'admin@gmail.com',
            'status' => '10',
            'created_at' => '1634329569',
            'updated_at' => '1634329569',
            'verification_token' => '00X1lMw1qf23hbVmI0izreeYqbDULp98_1634329569',
        ]);
        $this->insert('auth_assignment', [
            'item_name' => 'admin',
            'user_id' => '1',
            'created_at' => '1634329569',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211015_212241_create_user_admin cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211015_212241_create_user_admin cannot be reverted.\n";

        return false;
    }
    */
}

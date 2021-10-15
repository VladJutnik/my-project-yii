<?php

use yii\db\Migration;

/**
 * Class m211015_200557_auth_item_create_role
 */
class m211015_200557_auth_item_create_role extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->insert('auth_item', [
            'name' => 'admin',
            'type' => '1',
            'description' => 'Администратор',
        ]);
        $this->insert('auth_item', [
            'name' => 'user',
            'type' => '1',
            'description' => 'Пользователь',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211015_200557_auth_item_create_role cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211015_200557_auth_item_create_role cannot be reverted.\n";

        return false;
    }
    */
}

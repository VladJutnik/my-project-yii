<?php

use yii\db\Migration;

/**
 * Class m211016_112659_create_table_shop
 */
class m211016_112659_create_table_shop extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_info', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'status_view' => $this->integer()->notNull()->defaultValue(0),
        ]);
        //внешний ключ для user_id
        $this->addForeignKey(
            'fk-shop_info-user_id',
            'shop_info',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211016_112659_create_table_shop cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211016_112659_create_table_shop cannot be reverted.\n";

        return false;
    }
    */
}

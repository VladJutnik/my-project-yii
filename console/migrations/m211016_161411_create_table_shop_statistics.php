<?php

use yii\db\Migration;

/**
 * Class m211016_161411_create_table_shop_statistics
 */
class m211016_161411_create_table_shop_statistics extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('shop_statistics', [
            'id' => $this->primaryKey(),
            'shop_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'data' => $this->string('10')->notNull(),
            'type_case' => $this->string('50')->notNull(),
            'case' => $this->integer()->notNull(),
            'description' => $this->string()->notNull(),
        ]);
        //внешний ключ для user_id
        $this->addForeignKey(
            'fk-category-category_id',
            'shop_statistics',
            'category_id',
            'category',
            'id',
            'CASCADE'
        );
        //внешний ключ для user_id
        $this->addForeignKey(
            'fk-shop_info-shop_id',
            'shop_statistics',
            'shop_id',
            'shop_info',
            'id',
            'CASCADE'
        );
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211016_161411_create_table_shop_statistics cannot be reverted.\n";

        return false;
    }
}

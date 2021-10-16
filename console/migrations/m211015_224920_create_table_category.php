<?php

use yii\db\Migration;

/**
 * Class m211015_224920_create_table_category
 */
class m211015_224920_create_table_category extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('category', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'name' => $this->string()->notNull(),
            'status_view' => $this->integer()->notNull()->defaultValue(0),
        ]);
        //внешний ключ для user_id
        $this->addForeignKey(
            'fk-category-user_id',
            'category',
            'user_id',
            'user',
            'id',
            'CASCADE'
        );
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 1',
            'status_view' => '0',
        ]);
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 2',
            'status_view' => '0',
        ]);
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 3',
            'status_view' => '0',
        ]);
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 4',
            'status_view' => '0',
        ]);
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 5',
            'status_view' => '0',
        ]);
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 6',
            'status_view' => '0',
        ]);
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 7',
            'status_view' => '0',
        ]);
        $this->insert('category', [
            'user_id' => '1',
            'name' => 'Категория 8',
            'status_view' => '0',
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211015_224920_create_table_category cannot be reverted.\n";

        return false;
    }
}

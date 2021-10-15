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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m211015_224920_create_table_category cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m211015_224920_create_table_category cannot be reverted.\n";

        return false;
    }
    */
}

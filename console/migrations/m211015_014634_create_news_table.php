<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%news}}`.
 */
class m211015_014634_create_news_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        //TYPE_PK AUTO_INCREMENT будет превращено в int(11) NOT NULL AUTO_INCREMENT PRIMARY KEY тоже самое $this->primaryKey(),
        //TYPE_STRING  к строковому типу будет превращено в varchar(255) тоже самое $this->string()->notNull(),
        //TYPE_TEXT  к текстовому типу будет превращено в varchar(255) тоже самое $this->text(),,
        $this->createTable('{{%news}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string()->notNull(),
            'content' => $this->text(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%news}}');
    }
}

<?php

use yii\db\Migration;
use yii\db\Schema;

/**
 * Handles the creation of table `{{%file_stor}}`.
 */
class m240409_192200_create_file_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'fileName' => $this->string()->notNull(),
            'dateTime' => $this->integer()->notNull(),
            'path' => $this->string()->notNull(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%file}}');
    }
}

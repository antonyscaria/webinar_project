<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%webinars}}`.
 */
class m251003_074732_create_webinars_table extends Migration
{
public function safeUp()
    {
        $this->createTable('{{%webinars}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string()->notNull(),
            'description' => $this->text(),
            'event_id' => $this->string(50)->null(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
            'updated_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'),
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%webinars}}');
    }
}
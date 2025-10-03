<?php

use yii\db\Migration;

/**
 * Handles the creation of table `{{%webinar_registrants}}`.
 */
class m251003_074852_create_webinar_registrants_table extends Migration
{
public function safeUp()
    {
        $this->createTable('{{%webinar_registrants}}', [
            'id' => $this->primaryKey(),
            'webinar_id' => $this->integer()->notNull(),
            'first_name' => $this->string(100)->notNull(),
            'last_name' => $this->string(100)->notNull(),
            'email' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->defaultExpression('CURRENT_TIMESTAMP'),
        ]);

        $this->addForeignKey(
            'fk-webinar_registrants-webinar_id',
            '{{%webinar_registrants}}',
            'webinar_id',
            '{{%webinars}}',
            'id',
            'CASCADE'
        );
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk-webinar_registrants-webinar_id', '{{%webinar_registrants}}');
        $this->dropTable('{{%webinar_registrants}}');
    }
}
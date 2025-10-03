<?php

use yii\db\Migration;

class m251003_113849_add_gotowebinar_fields_to_webinar_registrants extends Migration
{
public function safeUp()
    {
        $this->addColumn('{{%webinar_registrants}}', 'registrant_key', $this->string()->null()->after('email'));
        $this->addColumn('{{%webinar_registrants}}', 'join_url', $this->string()->null()->after('registrant_key'));
        $this->addColumn('{{%webinar_registrants}}', 'status', $this->string(50)->null()->after('join_url'));
    }

    public function safeDown()
    {
        $this->dropColumn('{{%webinar_registrants}}', 'registrant_key');
        $this->dropColumn('{{%webinar_registrants}}', 'join_url');
        $this->dropColumn('{{%webinar_registrants}}', 'status');
    }
}
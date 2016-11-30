<?php

use yii\db\Migration;
use yii\db\Schema;

class m151205_111430_add_message_error_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%mail_message_error}}', [
            'id'         => Schema::TYPE_PK,
            'message_id' => Schema::TYPE_INTEGER,
            'sending_at' => Schema::TYPE_TIMESTAMP . ' DEFAULT CURRENT_TIMESTAMP',
            'error'      => Schema::TYPE_STRING . ' NOT NULL'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey('{{%mail_message_error_fk}}', '{{%mail_message_error}}',
            'message_id', '{{%mail_message}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%mail_message_error_fk}}', '{{%mail_message_error}}');

        $this->dropTable('{{%mail_message_error}}');
    }
}

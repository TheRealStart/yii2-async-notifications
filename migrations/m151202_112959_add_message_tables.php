<?php

use TRS\AsyncNotification\components\enums\MailStatus;
use yii\db\Migration;
use yii\db\Schema;

class m151202_112959_add_message_tables extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%mail_message}}', [
            'id'         => Schema::TYPE_PK,
            'status'     => Schema::TYPE_STRING . ' NOT NULL DEFAULT "' . MailStatus::_NEW . '"',
            'subject'    => Schema::TYPE_STRING . ' NOT NULL',
            'from'       => Schema::TYPE_STRING . ' NOT NULL',
            'body_text'  => Schema::TYPE_TEXT . ' NOT NULL',
            'body_html'  => Schema::TYPE_TEXT . ' NOT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL',
            'updated_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL'
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createTable('{{%mail_recipient}}', [
            'message_id' => Schema::TYPE_INTEGER . ' NOT NULL',
            'email'      => Schema::TYPE_STRING . ' NOT NULL',
            'name'       => Schema::TYPE_STRING
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey('{{%mail_message_recipient_fk}}', '{{%mail_recipient}}', 'message_id', '{{%mail_message}}', 'id', 'RESTRICT', 'CASCADE');

        $this->createTable('{{%mail_attachment}}', [
            'id'         => Schema::TYPE_PK,
            'message_id' => Schema::TYPE_INTEGER,
            'name'       => Schema::TYPE_STRING . '(2048)',
            'created_at' => Schema::TYPE_TIMESTAMP
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey('{{%mail_message_attachment}}', '{{%mail_attachment}}', 'message_id', '{{%mail_message}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%mail_message_attachment}}', '{{%mail_attachment}}');
        $this->dropTable('{{%mail_attachment}}');

        $this->dropForeignKey('{{%mail_message_recipient_fk}}', '{{%mail_recipient}}');
        $this->dropTable('{{%mail_recipient}}');

        $this->dropTable('{{%mail_message}}');
    }
}

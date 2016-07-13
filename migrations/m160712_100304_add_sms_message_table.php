<?php

use yii\db\Migration;

class m160712_100304_add_sms_message_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%sms_message}}', [
            'id'         => $this->primaryKey(),
            'body_text'  => $this->string()->notNull(),
            'status'     => $this->string()->notNull(),
            'try_count'  => $this->integer()->notNull(),
            'created_at' => $this->timestamp()->notNull(),
            'updated_at' => $this->timestamp()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->createTable('{{%sms_recipient}}', [
            'id'         => $this->primaryKey(),
            'message_id' => $this->integer()->notNull(),
            'phone'      => $this->string()->notNull(),
        ], 'ENGINE=InnoDB DEFAULT CHARSET=utf8');

        $this->addForeignKey('{{%sms_message_recipient_fk}}', '{{%sms_recipient}}', 'message_id', '{{%sms_message}}', 'id', 'RESTRICT', 'CASCADE');
    }

    public function safeDown()
    {
        $this->dropForeignKey('{{%sms_message_recipient_fk}}', '{{%sms_recipient}}');
        $this->dropTable('{{%sms_recipient}}');
        $this->dropTable('{{%sms_message}}');
    }
}

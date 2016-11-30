<?php

use yii\db\Migration;

class m160212_132037_add_phone_id_table extends Migration
{
    public function safeUp()
    {
        $this->createTable('{{%push_device}}', [
            'id'         => $this->primaryKey(),
            'type'       => $this->string()->notNull(),
            'device_id'  => $this->string()->notNull(),
            'owner_id'   => $this->string(),
            'created_at' => $this->timestamp()->notNull(),
        ]);

        $this->createIndex('{{%push_device_device_id}}', '{{%push_device}}', 'device_id');
        $this->createIndex('{{%push_device_owner_id}}', '{{%push_device}}', 'owner_id');
    }

    public function safeDown()
    {
        $this->dropIndex('{{%push_device_device_id}}', '{{%push_device}}');
        $this->dropIndex('{{%push_device_owner_id}}', '{{%push_device}}');

        $this->dropTable('{{%push_device}}');
    }
}

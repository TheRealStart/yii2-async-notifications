<?php

use yii\db\Schema;
use yii\db\Migration;

class m160212_132037_add_phone_id_table extends Migration
{
    const PUSH_DEVICE = 'push_device';
    public function safeUp()
    {
        $this->createTable(static::PUSH_DEVICE, [
            'id' => Schema::TYPE_PK,
            'type' => Schema::TYPE_STRING . ' NOT NULL',
            'device_id' => Schema::TYPE_STRING . '(128) NOT NULL',
            'owner_id' => Schema::TYPE_STRING . ' DEFAULT NULL',
            'created_at' => Schema::TYPE_TIMESTAMP . ' NOT NULL'
        ]);

        $this->createIndex(static::PUSH_DEVICE . '_device_id', static::PUSH_DEVICE, 'device_id');
        $this->createIndex(static::PUSH_DEVICE . '_owner_id', static::PUSH_DEVICE, 'owner_id');
    }

    public function safeDown()
    {
        $this->dropIndex(static::PUSH_DEVICE . '_owner_id', static::PUSH_DEVICE);
        $this->dropIndex(static::PUSH_DEVICE . '_device_id', static::PUSH_DEVICE);

        $this->dropTable(static::PUSH_DEVICE);
    }
}

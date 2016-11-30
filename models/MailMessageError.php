<?php

namespace TRS\AsyncNotification\models;

use TRS\AsyncNotification\models\base\MailMessageError as BaseMailMessageError;

/**
 * This is the model class for table "mail_message_error".
 */
class MailMessageError extends BaseMailMessageError
{
    public static function tableName()
    {
        return '{{%' . parent::tableName() . '}}';
    }
}

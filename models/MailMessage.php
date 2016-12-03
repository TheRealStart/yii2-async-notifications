<?php

namespace TRS\AsyncNotification\models;

use TRS\AsyncNotification\components\enums\MailStatus;
use TRS\AsyncNotification\models\base\MailMessage as BaseMailMessage;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "mail_message".
 */
class MailMessage extends BaseMailMessage
{
    public static function tableName()
    {
        return '{{%' . parent::tableName() . '}}';
    }

    /**
     * @return ActiveQuery
     */
    public static function findSendable()
    {
        return static::find()->andWhere([ 'status' => [ MailStatus::_NEW, MailStatus::FAIL ] ]);
    }
}

<?php

namespace TRS\AsyncNotification\models;

use Yii;
use \TRS\AsyncNotification\models\base\MailMessageError as BaseMailMessageError;

/**
 * This is the model class for table "mail_message_error".
 */
class MailMessageError extends BaseMailMessageError
{
	public static function getDb()
	{
		//Replace it when moving model to separate application
		return Yii::$app->notification_db;
	}

	public static function tableName()
	{
		return '{{%' . parent::tableName() . '}}';
	}
}

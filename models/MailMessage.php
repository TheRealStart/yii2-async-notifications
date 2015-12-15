<?php

namespace TRS\AsyncNotification\models;

use Yii;
use \TRS\AsyncNotification\models\base\MailMessage as BaseMailMessage;
use yii\db\Connection;

/**
 * This is the model class for table "mail_message".
 */
class MailMessage extends BaseMailMessage
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

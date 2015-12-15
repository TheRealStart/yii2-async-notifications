<?php

namespace TRS\AsyncNotification\models;

use Yii;
use \TRS\AsyncNotification\models\base\MailRecipient as BaseMailRecipient;

/**
 * This is the model class for table "mail_recipient".
 */
class MailRecipient extends BaseMailRecipient
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

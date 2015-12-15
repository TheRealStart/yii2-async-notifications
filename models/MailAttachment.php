<?php

namespace TRS\AsyncNotification\models;

use Yii;
use \TRS\AsyncNotification\models\base\MailAttachment as BaseMailAttachment;

/**
 * This is the model class for table "mail_attachment".
 */
class MailAttachment extends BaseMailAttachment
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

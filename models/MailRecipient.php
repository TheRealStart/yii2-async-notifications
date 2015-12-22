<?php

namespace TRS\AsyncNotification\models;

use Yii;
use \TRS\AsyncNotification\models\base\MailRecipient as BaseMailRecipient;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mail_recipient".
 */
class MailRecipient extends BaseMailRecipient
{
	public static function tableName()
	{
		return '{{%' . parent::tableName() . '}}';
	}

	public function rules () {
		return ArrayHelper::merge(
			parent::rules(),
			[
				[['email'], 'email', 'skipOnEmpty' => false]
			]
		);
	}
}

<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 5:10 PM
 */

namespace TRS\AsyncNotification\components;


use Yii;
use TRS\AsyncNotification\components\providers\MailProviderInterface;
use yii\mail\BaseMessage;
use yii\swiftmailer\Message;

class MailProxy implements MailProviderInterface {
	/** @var  MailProxy */
	private static $instance;

	private $mailer;

	private function __construct() {
		$this->mailer = Yii::$app->getMailer();
	}

	/**
	 * @inheritdoc
	 */
	public function send(BaseMessage $message) {
		return $this->mailer->send($message);
	}

	/**
	 * @inheritdoc
	 */
	public function sendMultiple(array $messages)
	{
		return $this->mailer->sendMultiple($messages);
	}

	/**
	 * @inheritdoc
	 */
	public function getEmptyMessage() {
		return new Message();
	}


	public static function getInstance() {
		if (!isset(static::$instance))
			static::$instance = new MailProxy();

		return static::$instance;
	}
}
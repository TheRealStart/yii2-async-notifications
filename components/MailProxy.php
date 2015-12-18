<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 5:10 PM
 */

namespace TRS\AsyncNotification\components;


use TRS\AsyncNotification\components\providers\Mailer;
use TRS\AsyncNotification\components\providers\MailProviderInterface;
use TRS\AsyncNotification\models\forms\Message;

class MailProxy implements MailProviderInterface {
	/** @var  MailProxy */
	private static $instance;

	private $mailer;

	private function __construct() {
		$this->mailer = new Mailer();
	}

	/**
	 * @param Message $params
	 * @return bool|void
	 */
	public function send(Message $params) {
		return $this->mailer->send($params);
	}

	public static function getInstance() {
		if (!isset(static::$instance))
			static::$instance = new MailProxy();

		return static::$instance;
	}
}
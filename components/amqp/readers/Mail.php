<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/1/15
 * Time: 4:48 PM
 */

namespace TRS\AsyncNotification\components\amqp\readers;


use PhpAmqpLib\Message\AMQPMessage;
use TRS\AsyncNotification\components\amqp\MessageReader;
use Yii;
use yii\swiftmailer\Message;

class Mail extends MessageReader implements \TRS\AsyncNotification\components\amqp\interfaces\MessageReader {

	private $mailer;

	public function init()
	{
		parent::init();

		$this->mailer = Yii::$app->getMailer();
	}


	public function read(AMQPMessage $msg)
	{
		$message = new Message();
	}
} 
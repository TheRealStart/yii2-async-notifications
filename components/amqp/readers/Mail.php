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
use TRS\AsyncNotification\components\Proxy;
use TRS\AsyncNotification\models\MailAttachment;
use TRS\AsyncNotification\models\MailMessage;
use TRS\AsyncNotification\models\MailRecipient;
use Yii;
use yii\base\ErrorException;

class Mail extends MessageReader implements \TRS\AsyncNotification\components\amqp\interfaces\MessageReader {

	private $mailer;

	public function init()
	{
		parent::init();

		$this->mailer = Yii::$app->getMailer();
	}


	public function read(AMQPMessage $amqpMessage)
	{
		$mailProxy = Proxy::getInstance();
		$message = $mailProxy->getEmptyMessage();
		$data = $this->getMessageBody($amqpMessage);
		$messageId = $data['id'];

		/** @var MailMessage $messageData */
		$messageData = MailMessage::find()->where(['id' => $messageId])->one();

		if (!$messageData) {
			$this->nack($amqpMessage, false);
			throw new \InvalidArgumentException(sprintf('Message with "%d" doesn\'t exist', $messageId));
		}

		/** @var MailRecipient[] $recipients */
		$recipients = $messageData->getMailRecipients()->all();

		if (empty($recipients)) {
			$this->nack($amqpMessage, false);
			throw new ErrorException(sprintf('No recipients for message with id "%d"', $messageId));
		}

		$message->setFrom([$messageData->from]);
		$message->setSubject($messageData->subject);

		foreach ($recipients as $recipient)
			$message->setTo($recipient->email);

		if (empty($messageData->body_text) && empty($messageData->body_html)) {
			$this->nack($amqpMessage, false);
			throw new ErrorException(sprintf('No text set for message with id "%d"', $messageId));
		}

		$message->setCharset('UTF-8');

		if (!empty($messageData->body_text))
			$message->setTextBody($messageData->body_text);

		if (!empty($messageData->body_html))
			$message->setHtmlBody($messageData->body_html);

		/** @var MailAttachment $attachments */
		$attachments = $messageData->getMailAttachments()->all();

		if (!empty($attachments)) {
			foreach ($attachments as $attachment) {
				//TODO Refactor this part when others will be finished. It is not working
				$message->attach($attachment->name);
			}
		}

		$mailProxy->send($message);
	}
} 
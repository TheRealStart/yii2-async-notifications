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
use TRS\AsyncNotification\components\MailProxy;
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
		$id = $this->getMessageBody($amqpMessage)['id'];

		/** @var MailMessage $messageData */
		if (is_null($model = MailMessage::find()->where(['id' => $id])->one())) {
			$this->nack($amqpMessage, false);
			throw new \InvalidArgumentException(sprintf('Message with "%d" doesn\'t exist', $id));
		}

		if (empty($model->body_text) && empty($model->body_html)) {
			$this->nack($amqpMessage, false);
			throw new ErrorException(sprintf('No text set for message with id "%d"', $id));
		}

		/** @var MailRecipient[] $recipients */
		if (empty($recipients = $model->getMailRecipients()->all())) {
			$this->nack($amqpMessage, false);
			throw new ErrorException(sprintf('No recipients for message with id "%d"', $id));
		}

		$mailProxy = MailProxy::getInstance();
		foreach ($recipients as $recipient) {
			/** @var  yii\swiftmailer\Message $message */
			$message = $mailProxy->getEmptyMessage();

			$message->setFrom([$model->from]);
			$message->setSubject($model->subject);

			$message->setTo([$recipient->email => $recipient->name]);

			$message->setCharset('UTF-8');

			if (!empty($messageData->body_text))
				$message->setTextBody($messageData->body_text);

			if (!empty($messageData->body_html))
				$message->setHtmlBody($messageData->body_html);

			/** @var MailAttachment $attachment */
			foreach ($model->getMailAttachments()->all() as $attachment){
					//TODO Refactor this part when others will be finished. It is not working
					$message->attach($attachment->name);
			}

			$mailProxy->send($message);
		}
	}
} 
<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/1/15
 * Time: 6:11 PM
 */

namespace TRS\AsyncNotification\components\amqp\readers;


use TRS\AsyncNotification\components\amqp\MessageReader;
use PhpAmqpLib\Message\AMQPMessage;
use TRS\AsyncNotification\components\enums\SmsStatus;
use TRS\AsyncNotification\components\SmsApi;
use TRS\AsyncNotification\models\SmsMessage;
use yii\base\ErrorException;

class Sms extends MessageReader{
	public function read(AMQPMessage $amqpMessage)
	{
		/** @var SmsMessage $message */
		if (is_null($message = SmsMessage::findOne(['id'=>($id = $this->getMessageBody($amqpMessage)['id']), 'status'=>[SmsStatus::_NEW, SmsStatus::ERROR]]))){
			throw new \InvalidArgumentException(sprintf('Message by id %d doesn\'t exist', $id));
		}

		/** @var SmsApi $smsApi */
		$smsApi = Yii::$app->get('sms');

		$message->try_count++;
		if (($message->status = $smsApi->send($message)) == SmsStatus::ERROR){
			if ($message->try_count >= $smsApi->resendLimit)
				$message->status = SmsStatus::CANCELED;
			else
				$this->nack($amqpMessage, false);
		}

		if (!$message->save()){
			throw new ErrorException('Failed to update message status');
		}

		return true;
	}
} 
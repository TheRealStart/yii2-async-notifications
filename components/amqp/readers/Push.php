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
use TRS\AsyncNotification\components\enums\DeviceOS;
use Yii;

class Push extends MessageReader{
	public function read(AMQPMessage $amqpMessage)
	{
		$data = $this->getMessageBody($amqpMessage);
		$apnsDevices = [];
		$gcmDevices  = [];
		foreach ($data['devices'] as $device)
			switch ($device[1]){
				case DeviceOS::ANDROID:
					$gcmDevices[] = $device[0];
					break;
				case DeviceOS::IOS:
					$apnsDevices[] = $device[0];
					break;
			}

		if (count($gcmDevices))
			Yii::$app->gcm->sendMulti($gcmDevices, $data['text'], $data['payloadData']);

		if (count($apnsDevices))
			Yii::$app->apns->sendMulti($apnsDevices, $data['text'], $data['payloadData']);

		$this->ack($amqpMessage);
	}
} 
<?php

namespace TRS\AsyncNotification\components\amqp\interfaces;

use PhpAmqpLib\Message\AMQPMessage;

interface MessageReader {
	/**
	 * @param AMQPMessage $message
	 * @return mixed
	 *
	 * Method used for all readers. It is called by Rabbit controller when receiving message.
	 */
	public function read(AMQPMessage $message);
}
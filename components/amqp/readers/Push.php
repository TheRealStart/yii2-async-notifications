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

class Push extends MessageReader{
	public function read(AMQPMessage $amqpMessage)
	{
	}
} 
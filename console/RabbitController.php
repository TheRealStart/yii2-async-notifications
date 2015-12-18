<?php
/**
 * User: Fungus
 * Date: 13.11.2015
 * Time: 16:01
 */

namespace TRS\AsyncNotification\console\controllers;

use TRS\AsyncNotification\components\amqp\Amqp;
use TRS\AsyncNotification\components\amqp\readers\Mail;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use TRS\AsyncNotification\components\enums\NotificationQueue;
use yii\console\Controller;
use PhpAmqpLib\Message\AMQPMessage;
use Yii;
use TRS\AsyncNotification\components\amqp\MessageReader;

class RabbitController extends Controller
{
    public $defaultAction = 'run';
    public $interpreters = [];

    public function init()
    {
        $this->interpreters = [
            NotificationQueue::MAIL => Mail::className()
        ];
    }
    public function actionRun()
    {
        /** @var AMQPStreamConnection $connection */
        $connection = Yii::$app->amqp->getConnection();
        $channel = $connection->channel();

        foreach($this->interpreters as $key => $value)
            $channel->queue_declare($key, false, true, false, true);

        $channel->exchange_declare(Yii::$app->amqp->exchange, Amqp::TYPE_DIRECT, false, true, false);

        foreach($this->interpreters as $key => $value)
            $channel->queue_bind($key, Yii::$app->amqp->exchange, $key);

        foreach($this->interpreters as $key => $value)
            $channel->basic_consume($key, '', false, false, false, false, [$this, 'callback']);

        while (count($channel->callbacks)) {
            $channel->wait();
        }
    }

    public function callback(AMQPMessage $msg)
    {
		/** @var string $routingKey */
        $routingKey = $msg->delivery_info['routing_key'];

		if (empty($this->interpreters[$routingKey]))
			throw new \InvalidArgumentException(Yii::t('error', 'Invalid routing key {key}', ['key' => $routingKey]));

		/** @var MessageReader $reader */
		$reader = new $this->interpreters[$routingKey];
		$reader->read($msg);
    }
}

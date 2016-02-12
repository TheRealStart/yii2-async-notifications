<?php
/**
 * User: Fungus
 * Date: 13.11.2015
 * Time: 15:09
 */

namespace TRS\AsyncNotification\components\amqp;

use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Json;
use PhpAmqpLib\Channel\AMQPChannel;
use PhpAmqpLib\Connection\AMQPStreamConnection;
use PhpAmqpLib\Message\AMQPMessage;


/**
 * AMQP wrapper.
 *
 * @property AMQPStreamConnection $connection AMQP connection.
 * @property AMQPChannel $channel AMQP channel.
 */
class Amqp extends Component
{
    const TYPE_TOPIC = 'topic';
    const TYPE_DIRECT = 'direct';
    const TYPE_HEADERS = 'headers';
    const TYPE_FANOUT = 'fanout';
    const TYPE_X_DELAYED_MESSAGE = 'x-delayed-message';

    /**
     * @var AMQPStreamConnection
     */
    protected static $ampqConnection;

    /**
     * @var AMQPChannel[]
     */
    protected $channels = [];

    /**
     * @var string
     */
    public $host = '127.0.0.1';

    /**
     * @var integer
     */
    public $port = 5672;

    /**
     * @var string
     */
    public $user;

    /**
     * @var string
     */
    public $password;

    /**
     * @var string
     */
    public $vhost = '/';

    /**
     * @var string
     */
    public $exchange = 'smart-city';

    /**
     * @var string
     */
    public $exchangeType = 'direct';

    /**
     * @var array
     */
    public $exchangeArgs = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if (empty($this->user)) {
            throw new Exception("Parameter 'user' was not set for AMQP connection.");
        }
        if (empty(self::$ampqConnection)) {
            self::$ampqConnection = new AMQPStreamConnection(
                $this->host,
                $this->port,
                $this->user,
                $this->password,
                $this->vhost
            );
        }
    }

    /**
     * Returns AMQP connection.
     *
     * @return AMQPStreamConnection
     */
    public function getConnection()
    {
        return self::$ampqConnection;
    }

    public function publish($message, $queue)
    {
        $connection = $this->getConnection();
        $channel = $connection->channel();

        $channel->queue_declare($queue, false, true, false, true);
        $channel
            ->exchange_declare($this->exchange, $this->exchangeType, false, true, false, false, false, $this->exchangeArgs);
        $channel->queue_bind($queue, $this->exchange, $queue);

        $msg = new AMQPMessage(Json::encode($message));
        $channel->basic_publish($msg, $this->exchange, $queue);
    }
}

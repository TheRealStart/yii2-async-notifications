<?php
/**
 * User: Fungus
 * Date: 16.11.2015
 * Time: 14:12
 */

namespace TRS\AsyncNotification\components\amqp;

use yii\base\Exception;
use yii\base\Object;
use yii\helpers\Console;
use PhpAmqpLib\Message\AMQPMessage;
use yii\helpers\Json;
use Yii;

abstract class MessageReader extends Object implements interfaces\MessageReader
{
    const MESSAGE_INFO = 0;
    const MESSAGE_ERROR = 1;

    /**
     * Logs info and error messages.
     *
     * @param $message
     * @param $type
     */
    public function log($message, $type = self::MESSAGE_INFO) {
        $format = [$type == self::MESSAGE_ERROR ? Console::FG_RED : Console::FG_BLUE];
        Console::stdout(Console::ansiFormat($message . PHP_EOL, $format));
    }

	/**
	 * @param AMQPMessage $msg
	 * @return bool
	 *
	 * Message processed and marked as delivered
	 */
	public function ack(AMQPMessage $msg)
    {
        $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']);
        return true;
    }

	/**
	 * @param AMQPMessage $msg
	 * @param bool $requeue
	 * @return bool
	 *
	 * Message processing failed and will be marked for resending
	 */
	public function nack(AMQPMessage $msg, $requeue = true)
    {
        $msg->delivery_info['channel']->basic_nack($msg->delivery_info['delivery_tag'], false, $requeue);
        return true;
    }

	/**
	 * @param AMQPMessage $msg
	 * @return bool
	 *
	 * Cancels message processing and removing it from queue. Message will be marked as failed.
	 */
	public function cancel(AMQPMessage $msg)
    {
        $msg->delivery_info['channel']->basic_cancel($msg->delivery_info['consumer_tag']);
        return true;
    }

    public function getMessageBody(AMQPMessage $msg)
    {
        try{
            $result = Json::decode($msg->body);
            if (!is_array($result)){
                $this->log("Json parse error\r\n", self::MESSAGE_ERROR);
                $this->nack($msg, false);
                Yii::$app->end();
            }
            return $result;
        } catch (Exception $ex){
            $this->log("Json decode error: ".$ex->getMessage()."\r\n", self::MESSAGE_ERROR);
            $this->nack($msg, false);
        }

		return null;
    }

	public abstract function read(AMQPMessage $msg);
}
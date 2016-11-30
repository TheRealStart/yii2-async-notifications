<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 7/19/16
 * Time: 1:35 PM
 */

namespace TRS\AsyncNotification\components\providers;

use TRS\AsyncNotification\components\enums\NotificationQueue;
use Yii;

class Push
{
    private $text;
    private $payloadData = [];
    private $devices     = [];

    /**
     * @param string $text
     */
    public function setText($text)
    {
        $this->text = $text;
    }

    /**
     * @param array $payloadData
     */
    public function setPayloadData(array $payloadData)
    {
        $this->payloadData = $payloadData;
    }

    /**
     * @param string $device_id
     * @param string $device_os
     */
    public function addTo($device_id, $device_os)
    {
        $this->devices[] = [ $device_id, $device_os ];
    }

    public function send()
    {
        Yii::$app->amqp->publish([ 'text' => $this->text, 'payloadData' => $this->payloadData, 'devices' => $this->devices ], NotificationQueue::PUSH);
    }
}
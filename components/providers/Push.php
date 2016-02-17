<?php
/**
 * Date: 2/17/16
 * Time: 2:58 PM
 */

namespace TRS\AsyncNotification\components\providers;


use bryglen\apnsgcm\Apns;
use bryglen\apnsgcm\ApnsGcm;
use TRS\AsyncNotification\components\enums\PushType;
use TRS\AsyncNotification\models\PushDevice;
use yii\base\NotSupportedException;
use TRS\AsyncNotification\components\interfaces\Provider;
use yii\base\UnknownPropertyException;
use yii\helpers\ArrayHelper;
use Yii;
use bryglen\apnsgcm\Gcm;

class Push implements Provider
{
    private $apnsDevices = [];
    private $gcmDevices = [];

    private $data = [];

    private $text = '';

    public function __construct($template, array $data = [])
    {
        $this->text = $template;
        $this->data = $data;
    }

    public function addTo(array $recipient)
    {
        foreach ($recipient as $deviceData) {
            /** @var PushDevice[]|null $devices */
            $devices = PushDevice::find()
                ->where(PushDevice::tableName() . ".{{device_id}} = :device_id OR " . PushDevice::tableName() . ".{{owner_id}} = :owner_id",
                    [':device_id' => strval($deviceData), ':owner_id' => strval($deviceData)])
                ->all();

            if (!empty($devices)) {
                foreach ($devices as $device) {
                    if ($device->type == PushType::ANDROID)
                        $this->gcmDevices[] = $device->device_id;
                    else if ($device->type == PushType::IOS)
                        $this->apcnDevices[] = $device->device_id;
                }
            }
        }
    }

    public function attach($path)
    {
        throw new NotSupportedException();
    }

    public function embed($path)
    {
        throw new NotSupportedException();
    }

    public function addData(array $data)
    {
        if (!empty($data))
            $this->data = ArrayHelper::merge($this->data, $data);
    }

    //TODO make is async
    public function send()
    {
        try {
            /** @var Gcm $gcm */
            $gcm = Yii::$app->gcm;
            $gcm->sendMulti($this->gcmDevices, $this->text, $this->data);
        } catch (UnknownPropertyException $error) {

        }

        try {
            /** @var Apns $apns */
            $apns = Yii::$app->apns;
            $apns->sendMulti($this->apnsDevices, $this->text, $this->data);
        } catch (UnknownPropertyException $error) {

        }
    }
}
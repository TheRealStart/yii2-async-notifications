<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 7/12/16
 * Time: 2:12 PM
 */

namespace TRS\AsyncNotification\components;

use TRS\AsyncNotification\components\interfaces\SmsTransport;
use TRS\AsyncNotification\models\SmsMessage;
use Yii;
use yii\base\Component;
use yii\base\InvalidConfigException;

class SmsApi extends Component
{

    /**
     * @var int How many times to resend in case of failure
     */
    public $resendLimit = 3;

    private $_transport = [];

    /**
     * @param SmsMessage $message
     * @return bool
     */
    public function send(SmsMessage $message)
    {
        return $this->getTransport()->send($message) > 0;
    }

    /**
     * @param array|SmsTransport $transport
     */
    public function setTransport($transport)
    {
        if (!is_array($transport) && !is_object($transport))
            throw new InvalidConfigException(sprintf('"%s"::transport should be either object or array, "%s" given.', get_class($this), gettype($transport)));

        $this->_transport = $transport;
    }

    /**
     * @param array $config
     * @return SmsTransport
     */
    protected function createTransport(array $config)
    {
        return $this->createSwiftObject($config);
    }

    /**
     * @return object
     */
    protected function createSwiftObject(array $config)
    {
        if (!isset( $config['class'] ))
            throw new InvalidConfigException('Object configuration must be an array containing a "class" element.');

        if (isset( $config['constructArgs'] )) {
            $args = [];
            foreach ($config['constructArgs'] as $value) {
                if (is_array($value) && isset( $value['class'] )) {
                    $args[] = $this->createSwiftObject($value);
                } else {
                    $args[] = $value;
                }
            }
            $object = Yii::createObject($config['class'], $args);
        } else {
            $object = Yii::createObject($config['class']);
        }

        unset( $config['class'] );

        if (count($config)) {
            $reflection = new \ReflectionObject($object);
            foreach ($config as $name => $value)
                if ($reflection->hasProperty($name) && $reflection->getProperty($name)->isPublic())
                    $object->$name = $value;
        }

        return $object;
    }

    /**
     * @return SmsTransport
     */
    public function getTransport()
    {
        if (!is_object($this->_transport)) {
            $this->_transport = $this->createTransport($this->_transport);
        }

        return $this->_transport;
    }
}

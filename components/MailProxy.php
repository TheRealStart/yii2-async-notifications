<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 5:10 PM
 */

namespace TRS\AsyncNotification\components;


use TRS\AsyncNotification\components\interfaces\Mailer;
use Yii;
use yii\mail\BaseMessage;

class MailProxy implements Mailer
{
    /** @var  MailProxy */
    private static $instance;

    private $mailer;

    private function __construct()
    {
        $this->mailer = Yii::$app->getMailer();
    }

    /**
     * @inheritdoc
     */
    public function send(BaseMessage $message)
    {
        return $this->mailer->send($message);
    }

    /**
     * @inheritdoc
     */
    public function sendMultiple(array $messages)
    {
        return $this->mailer->sendMultiple($messages);
    }

    /**
     * @inheritdoc
     */
    public function getEmptyMessage()
    {
        return $this->mailer->compose();
    }


    public static function getInstance()
    {
        if (!isset( static::$instance ))
            static::$instance = new MailProxy();

        return static::$instance;
    }
}
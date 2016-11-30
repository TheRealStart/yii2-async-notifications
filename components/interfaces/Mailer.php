<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 5:27 PM
 */

namespace TRS\AsyncNotification\components\interfaces;


use yii\mail\BaseMessage;


/**
 * Interface Provider
 * @package TRS\AsyncNotification\components\providers
 *
 * This interface was build because MailerInterface provided by Yii contains compose method,
 * which requires setting view name.
 *
 * I do not need it in this case because messages are already rendered to database and just required
 * to be set correctly in message object that can be build without compose method.
 */
interface Mailer
{
    /**
     * @param BaseMessage $message
     * @return bool
     */
    public function send(BaseMessage $message);

    /**
     * @param BaseMessage[] $messages
     * @return int
     */
    public function sendMultiple(array $messages);

    /**
     * @return BaseMessage
     *
     * Returns new instance of message class that is required or current provider
     */
    public function getEmptyMessage();
} 
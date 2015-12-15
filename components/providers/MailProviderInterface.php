<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 5:27 PM
 */

namespace TRS\AsyncNotification\components\providers;


use TRS\AsyncNotification\models\forms\Message;

interface MailProviderInterface {
	/**
	 * @param Message $params
	 * @return boolean
	 */
	public function send(Message $params);
} 
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
	 * @param $templateName
	 * @param array $recipients
	 * @param array $data
	 * @return mixed
	 */
	public function send($templateName, array $recipients, array $data = []);
} 
<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 5:27 PM
 */

namespace TRS\AsyncNotification\components\providers;


use TRS\AsyncNotification\models\forms\Message;
use yii\web\View;
use Yii;

class MailerProvider implements MailProviderInterface{
	/**
	 * @param Message $params
	 * @return bool
	 */
	public function send(Message $params)
	{
		/** @var View $view */
		$view = Yii::$app->getView();
//		$view->render()

		return true;
	}

} 
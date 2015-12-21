<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/5/15
 * Time: 5:27 PM
 */

namespace TRS\AsyncNotification\components\providers;


use TRS\AsyncNotification\components\enums\NotificationQueue;
use TRS\AsyncNotification\components\helpers\Error;
use TRS\AsyncNotification\components\helpers\NotificationHelper;
use TRS\AsyncNotification\models\base\MailMessage;
use TRS\AsyncNotification\models\MailRecipient;
use yii\base\ErrorException;
use yii\web\View;
use Yii;

class Mailer
{
	/**
	 * @inheritdoc
	 */
	public function send($templateName, array $recipients, array $data = [])
	{
		$message           = new MailMessage();
		$params            = NotificationHelper::getMailParams();
		$templateAlias     = str_replace('.', '/', $templateName);
		$messageRecipients = [];
		$viewPath          = $params->viewPath;
		$subjectCategory   = $params->subjectCategory;

		/** @var View $view */
		$view     = Yii::$app->getView();
		$bodyText = $view->render($viewPath . '/' . $templateAlias . '.txt.php');
		$bodyHtml = $view->render($viewPath . '/' . $templateAlias . '.html.php');
		$subject  = Yii::t($subjectCategory, $templateName);

		if ( empty( $recipients ) )
			throw new \InvalidArgumentException( 'Recipients list is blank' );

		$message->load([
			'MailMessage' => [
				'subject'   => $subject,
				'from'      => $params->from,
				'body_text' => $bodyText,
				'body_html' => $bodyHtml
			]
		]);

		if ( !$message->save() ) {
			throw new ErrorException(
				'Failed to save message with errors: ' . Error::processToString($message->getErrors()) );
		}

		foreach ( $recipients as $recipient ) {
			$model             = new MailRecipient();
			$model->message_id = $message->id;
			$model->email      = $recipient;

			if ( !$model->save() ) {
				MailRecipient::deleteAll(['message_id' => $message->id]);
				$message->delete();

				throw new \InvalidArgumentException(
					sprintf('Failed to add recipient "%s" with error: "%s"',
						$recipient, implode(', ', $model->getErrors(['email']))) );
			}

			$messageRecipients[] = $model;
		}

		Yii::$app->amqp->publish(['id' => $message->id], NotificationQueue::MAIL);

		return true;
	}

} 
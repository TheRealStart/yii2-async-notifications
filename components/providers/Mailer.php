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
use TRS\AsyncNotification\components\interfaces\Provider;
use TRS\AsyncNotification\models\base\MailMessage;
use TRS\AsyncNotification\models\MailRecipient;
use Yii;
use yii\base\ErrorException;
use yii\base\NotSupportedException;
use yii\helpers\ArrayHelper;
use yii\web\View;

class Mailer implements Provider
{
    private $template;

    private $data;

    private $subjectData = [];

    private $rcptTo = [];

    private $attachment;

    private $embeded;

    public function __construct($template, array $data = [])
    {
        $this->template = $template;
        $this->data     = $data;

        $this->buildSubjectData();
    }

    /**
     * @param array $data
     *
     * Reads data from parameters or from data attribute in object and
     * filters none-string data.
     *
     * Result stored in subjectData.
     */
    private function buildSubjectData(array $data = [])
    {
        $processData = $data;

        if (empty( $processData )) {
            $processData = $this->data;
        }

        foreach ($processData as $key => $item) {
            if (!is_string($item))
                unset( $processData[$key] );
        }

        $this->subjectData = ArrayHelper::merge($this->subjectData,
            $processData);
    }

    /**
     * @inheritdoc
     */
    public function addTo(array $recipients)
    {
        foreach ($recipients as $key => $value) {
            if (filter_var($key, FILTER_VALIDATE_EMAIL)) {
                $this->rcptTo[$key] = $value;
                continue;
            }

            if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException(
                    'Invalid recipient provided. Recepients format is ["email" => "name", "email2" => "name2"] or ["email@email.em", "email2@email.em"]. Nor key nor value are valid email');
            }

            $this->rcptTo[$value] = '';
        }
    }

    /**
     * @inheritdoc
     */
    public function attach($path)
    {
        //TODO add attachment processing
        throw new NotSupportedException();
    }

    /**
     * @inheritdoc
     */
    public function embed($path)
    {
        //TODO add embeded processing
        throw new NotSupportedException();
    }

    /**
     * @inheritdoc
     */
    public function addData(array $data)
    {
        ArrayHelper::merge($this->data, $data);
        $this->buildSubjectData($data);
    }

    /**
     * @inheritdoc
     */
    public function send()
    {
        $message         = new MailMessage();
        $params          = NotificationHelper::getMailParams();
        $templateAlias   = str_replace('.', '/', $this->template);
        $viewPath        = $params->viewPath;
        $subjectCategory = $params->subjectCategory;

        /** @var View $view */
        $view     = Yii::$app->getView();
        $bodyText = $view->render($viewPath . '/' . $templateAlias . '.txt.php', $this->data);
        $bodyHtml = $view->render($viewPath . '/' . $templateAlias . '.html.php', $this->data);
        $subject  = Yii::t($subjectCategory, $this->template, $this->subjectData);

        if (empty( $this->rcptTo ))
            throw new \InvalidArgumentException('Recipients list is blank');

        $message->load([
            'MailMessage' => [
                'subject'   => $subject,
                'from'      => $params->from,
                'body_text' => $bodyText,
                'body_html' => $bodyHtml
            ]
        ]);

        if (!$message->save()) {
            throw new ErrorException(
                'Failed to save message with errors: ' . Error::processToString($message->getErrors()));
        }

        foreach ($this->rcptTo as $email => $name) {
            $model             = new MailRecipient();
            $model->message_id = $message->id;
            $model->email      = $email;
            $model->name       = $name;

            if (!$model->save()) {
                MailRecipient::deleteAll([ 'message_id' => $message->id ]);
                $message->delete();

                throw new \InvalidArgumentException(
                    sprintf('Failed to add recipient "%s" with error: "%s"',
                        $email, implode(', ', $model->getErrors([ 'email' ]))));
            }

        }

        Yii::$app->amqp->publish([ 'id' => $message->id ], NotificationQueue::MAIL);

        return true;
    }

} 
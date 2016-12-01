<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/1/15
 * Time: 4:48 PM
 */

namespace TRS\AsyncNotification\components\amqp\readers;


use PhpAmqpLib\Message\AMQPMessage;
use TRS\AsyncNotification\components\amqp\MessageReader;
use TRS\AsyncNotification\components\enums\MailStatus;
use TRS\AsyncNotification\components\MailProxy;
use TRS\AsyncNotification\models\MailAttachment;
use TRS\AsyncNotification\models\MailMessage;
use TRS\AsyncNotification\models\MailMessageError;
use TRS\AsyncNotification\models\MailRecipient;
use Yii;
use yii\base\ErrorException;
use yii\mail\MailerInterface;
use yii\helpers\ArrayHelper;

class Mail extends MessageReader implements \TRS\AsyncNotification\components\amqp\interfaces\MessageReader
{

    /** @var MailerInterface */
    private $mailer;
    /** @var  MailMessage */
    private $messageData;

    private $defaultAllowedNumErrors = 3;

    private $allowedNumErrors;

    public function init()
    {
        parent::init();

        $notificationParams = ArrayHelper::getValue(\Yii::$app->params, 'notification', []);
        $this->allowedNumErrors = ArrayHelper::getValue($notificationParams,
            'allowedNumErrors', $this->defaultAllowedNumErrors);
        $this->mailer = Yii::$app->getMailer();
    }



    public function read(AMQPMessage $amqpMessage)
    {
        $mailProxy = MailProxy::getInstance();
        $message   = $mailProxy->getEmptyMessage();
        $data      = $this->getMessageBody($amqpMessage);
        $messageId = $data['id'];

        $this->selectMessageData($messageId);
        $messageData = $this->getMessageData();

        if (!$messageData) {
            $this->nack($amqpMessage, false);
            $error = sprintf('Message with "%d" doesn\'t exist', $messageId);
            $this->processingError($error);
            throw new \InvalidArgumentException($error);
        }

        /** @var MailRecipient[] $recipients */
        $recipients = $messageData->getMailRecipients()->all();

        if (empty( $recipients )) {
            $this->nack($amqpMessage, false);
            $error = sprintf('No recipients for message with id "%d"', $messageId);
            $this->processingError($error);
            throw new ErrorException($error);
        }

        $message->setFrom([ $messageData->from ]);
        $message->setSubject($messageData->subject);

        foreach ($recipients as $recipient) {
            $message->setTo([ $recipient->email => $recipient->name ]);
        }

        if (empty( $messageData->body_text ) && empty( $messageData->body_html )) {
            $this->nack($amqpMessage, false);
            $error = sprintf('No text set for message with id "%d"', $messageId);
            $this->processingError($error);
            throw new ErrorException($error);
        }

        $message->setCharset('UTF-8');

        if (!empty( $messageData->body_text ))
            $message->setTextBody($messageData->body_text);

        if (!empty( $messageData->body_html ))
            $message->setHtmlBody($messageData->body_html);

        /** @var MailAttachment $attachments */
        $attachments = $messageData->getMailAttachments()->all();

        //It doesn't work actually so better turn it off for a while
        if (!empty( $attachments ) && false) {
            foreach ($attachments as $attachment) {
                //TODO Refactor this part when others will be finished. It is not working
                $message->attach($attachment->name);
            }
        }

        try {
            $mailProxy->send($message);
            $this->ack($amqpMessage);
            $this->processingSuccessful();
        } catch ( \Exception $error ) {
            $this->nack($amqpMessage, true);
            $this->processingFailed($error->getMessage());
            throw $error;
        }
    }

    protected function selectMessageData($messageId) {
        /** @var MailMessage $result */
        $this->messageData = MailMessage::find()->where([ 'id' => $messageId,
            'status' => [ MailStatus::_NEW, MailStatus::FAIL ] ])->one();

        $errors = $this->messageData->getMailMessageErrors()->all();

        //Do not proceed messages that has more than allowed number of errors
        if ( $errors && count($errors) >= $this->allowedNumErrors ) {
            $this->messageData = null;
        }
    }

    /**
     * @param $messageId
     * @return MailMessage|null
     */
    protected function getMessageData() {
        return $this->messageData;
    }

    protected function processingFailed($reason = '') {
        $messageData = $this->getMessageData();
        $messageData->status = MailStatus::FAIL;
        $messageData->save();

        $this->recordError($messageData->id, $reason);
    }

    protected function processingError($reason) {
        $messageData = $this->getMessageData();
        $messageData->status = MailStatus::ERROR;
        $messageData->save();

        $this->recordError($messageData->id, $reason);
    }

    protected function processingSuccessful() {
        $messageData = $this->getMessageData();
        $messageData->status = MailStatus::SENT;
        $messageData->save();
    }

    protected function recordError($messageId, $error) {
        $error = new MailMessageError();
        $error->message_id = $messageId;
        $error->error = $error;
        $error->save();
    }
} 
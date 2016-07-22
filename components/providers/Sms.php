<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 7/12/16
 * Time: 11:23 AM
 */

namespace TRS\AsyncNotification\components\providers;


use TRS\AsyncNotification\components\enums\NotificationQueue;
use TRS\AsyncNotification\components\enums\SmsStatus;
use TRS\AsyncNotification\models\SmsMessage;
use TRS\AsyncNotification\models\SmsRecipient;
use Yii;

class Sms
{
    /**
     * @param string $text body message
     * @param string|array $recipient one or more phone numbers
     * @return bool
     */
    public static function send($text, $recipient){

        $rcpList = is_array($recipient) ? $recipient : [$recipient];

        if (count($rcpList) == 0)
            throw new \InvalidArgumentException('Empty recipient list');

        $transaction = Yii::$app->db->beginTransaction();

        $message = new SmsMessage();
        $message->setAttributes([
          'body_text' => $text,
          'status'    => SmsStatus::_NEW,
          'try_count' => 0,
        ]);
        if (!$message->save()) {
            $transaction->rollBack();
            throw new \InvalidArgumentException('Failed to save message with error "'.$message->getErrors('body_text').'"');
        }

        foreach ($rcpList as $value){
            $model = new SmsRecipient();
            $model->message_id = $message->id;
            $model->phone = $value;
            if (!$model->save()){
                $transaction->rollBack();
                throw new \InvalidArgumentException(sprintf('Failed to add recipient "%s" with error "%s"',$value,$model->getErrors('phone')));
            }
        }

        $transaction->commit();
        Yii::$app->amqp->publish(['id' => $message->id], NotificationQueue::SMS);
        return true;
    }
}

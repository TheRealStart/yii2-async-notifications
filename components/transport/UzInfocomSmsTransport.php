<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 7/13/16
 * Time: 1:55 PM
 */

namespace TRS\AsyncNotification\components\transport;

use TRS\AsyncNotification\components\interfaces\SmsTransport;
use TRS\AsyncNotification\models\SmsMessage;
use TRS\AsyncNotification\models\SmsRecipient;
use Yii;

class UzInfocomSmsTransport implements SmsTransport
{

    public $url;
    public $login;
    public $password;

    /**
     * @param SmsMessage $message
     * @return int
     */
    public function send(SmsMessage $message)
    {
        $sent = 0;
        /** @var SmsRecipient $model */
        foreach ($message->getSmsRecipients()->all() as $model) {
            $ch = curl_init($this->url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'login' => $this->login,
                'passw' => $this->password,
                'phone' => $model->getCleanPhone(),
                'text'  => $message->body_text,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            if (curl_errno($ch) != 0) {
                Yii::error(sprintf('request to api fails with "%s"', curl_error($ch)), __METHOD__);
                return $sent;
            }
            curl_close($ch);
            if ($httpCode == 200) {
                $object = new \SimpleXMLElement($response);
                if (intval($object->result['status']) == 0) {
                    $sent++;
                    Yii::info(sprintf('sms "%s" to "%s" sended', $message->body_text, $model->phone), __METHOD__);
                } else {
                    Yii::warning(sprintf('sms "%s" to "%s" fails with "%s"', $message->body_text, $model->phone, $object->result), __METHOD__);
                }
            } else {
                Yii::warning(sprintf('http code different than 200. %d %s', $httpCode, $response), __METHOD__);
            }
        }
        return $sent;
    }
}

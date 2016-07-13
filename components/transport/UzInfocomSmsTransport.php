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

class UzInfocomSmsTransport implements SmsTransport {

    public $url = 'https://91.212.89.137/mobile.php';
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
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, [
                'login' => $this->login,
                'passw' => $this->password,
                'phone' => $model->getCleanPhone(),
                'text'  => $message->body_text,
            ]);
            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            Yii::info(sprintf('Response %s Code %d', $response, $httpCode), __METHOD__);
        }
        return $sent;
    }
}

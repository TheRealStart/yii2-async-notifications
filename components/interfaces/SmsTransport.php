<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 7/13/16
 * Time: 11:56 AM
 */

namespace TRS\AsyncNotification\components\interfaces;

use TRS\AsyncNotification\models\SmsMessage;

interface SmsTransport {
    /**
     * @param SmsMessage $message
     * @return string one of \TRS\AsyncNotification\components\enums\SmsStatus constant
     */
    public function send(SmsMessage $message);
}

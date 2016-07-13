<?php
/**
 * Created by IntelliJ IDEA.
 * User: user
 * Date: 7/12/16
 * Time: 2:12 PM
 */

namespace TRS\AsyncNotification\components;

use TRS\AsyncNotification\models\SmsMessage;
use yii\base\Component;
use Yii;

class SmsApi extends Component
{

    /**
     * @var int How many times to resend in case of failure
     */
    public $resendLimit = 3;

    /**
     * @param SmsMessage $message
     * @return string send status
     */
    public function send(SmsMessage $message)
    {
        // TODO: Implement send() method.
        // TODO: Do not forget increment message try count property!
    }
}

<?php

namespace TRS\AsyncNotification\models;

use \TRS\AsyncNotification\models\base\SmsMessage as BaseSmsMessage;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sms_message".
 */
class SmsMessage extends BaseSmsMessage
{
    public function rules(){
        return ArrayHelper::merge(
            parent::rules(),
            [
                [['body_text'], 'trim']
            ]
        );
    }
}

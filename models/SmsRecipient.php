<?php

namespace TRS\AsyncNotification\models;

use TRS\AsyncNotification\models\base\SmsRecipient as BaseSmsRecipient;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "sms_recipient".
 */
class SmsRecipient extends BaseSmsRecipient
{
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                [ [ 'phone' ], 'trim' ]
            ]
        );
    }

    public function getCleanPhone($addPlus = true)
    {
        return ( $addPlus ? '+' : '' ) . str_replace([ '+', '-', ' ', '(', ')' ], '', $this->phone);
    }

}

<?php

namespace TRS\AsyncNotification\models;

use TRS\AsyncNotification\components\enums\MailStatus;
use TRS\AsyncNotification\models\base\MailMessage as BaseMailMessage;
use yii\db\ActiveQuery;
use yii\db\Expression;
use yii\db\Query;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mail_message".
 */
class MailMessage extends BaseMailMessage
{
    private static $defaultAllowedNumErrors = 3;

    private static $allowedNumErrors;

    public static function tableName()
    {
        return '{{%' . parent::tableName() . '}}';
    }

    /**
     * @return ActiveQuery
     */
    public static function findSendable()
    {
        return static::find()
            ->addSelect( static::tableName() . '.*' )
            ->addSelect( new Expression('COUNT(' . MailMessageError::tableName() . '.message_id) AS `count_errors`') )
            ->andWhere([ 'status' => [ MailStatus::_NEW, MailStatus::FAIL ] ])
            ->joinWith([ 'mailMessageErrors' ])
            ->andHaving([ '<=', 'count_errors', static::getNumAllowedErrors() ]);
    }

    private static function getNumAllowedErrors() {

        if ( !isset(static::$allowedNumErrors) ) {
            $notificationParams = ArrayHelper::getValue(\Yii::$app->params, 'notification', []);
            static::$allowedNumErrors = ArrayHelper::getValue($notificationParams,
                'allowedNumErrors', static::$defaultAllowedNumErrors);
        }

        return static::$allowedNumErrors;
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/18/15
 * Time: 11:14 AM
 */

namespace TRS\AsyncNotification\components\helpers;

use TRS\AsyncNotification\models\MailParameters;
use Yii;
use yii\base\InvalidParamException;

class NotificationHelper
{
    /**
     * @return MailParameters
     * @throws \yii\base\InvalidParamException
     */
    public static function getMailParams()
    {
        $appParams = Yii::$app->params['notification']['mail'];
        $model     = new MailParameters();
        $model->load([ 'MailParameters' => $appParams ]);

        if (!$model->validate()) {
            throw new InvalidParamException(
                'Parameters are invalid. See errors below.' . Error::processToString($model->getErrors()));
        }

        return $model;
    }
} 
<?php

namespace TRS\AsyncNotification\models;

use TRS\AsyncNotification\components\validators\FileExists;
use TRS\AsyncNotification\models\base\MailAttachment as BaseMailAttachment;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "mail_attachment".
 */
class MailAttachment extends BaseMailAttachment
{
    public static function tableName()
    {
        return '{{%' . parent::tableName() . '}}';
    }

    public function rules()
    {
        return ArrayHelper::merge(parent::rules(),
            [ [ 'name' ], FileExists::className(), 'skipOnEmpty' => false ]
        );
    }


}

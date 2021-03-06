<?php

namespace TRS\AsyncNotification\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_recipient".
 *
 * @property integer $message_id
 * @property string $email
 * @property string $name
 *
 * @property \TRS\AsyncNotification\models\MailMessage $message
 */
class MailRecipient extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_recipient';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'message_id', 'email' ], 'required' ],
            [ [ 'message_id' ], 'integer' ],
            [ [ 'email', 'name' ], 'string', 'max' => 255 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'message_id' => Yii::t('app', 'Message ID'),
            'email'      => Yii::t('app', 'Email'),
            'name'       => Yii::t('app', 'Name'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(\TRS\AsyncNotification\models\MailMessage::className(), [ 'id' => 'message_id' ]);
    }


}

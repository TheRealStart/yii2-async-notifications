<?php

namespace TRS\AsyncNotification\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_message_error".
 *
 * @property integer $id
 * @property integer $message_id
 * @property string $sending_at
 * @property string $error
 *
 * @property \TRS\AsyncNotification\models\MailMessage $message
 */
class MailMessageError extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_message_error';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['message_id'], 'integer'],
            [['sending_at'], 'safe'],
            [['error'], 'required'],
            [['error'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'message_id' => Yii::t('app', 'Message ID'),
            'sending_at' => Yii::t('app', 'Sending At'),
            'error' => Yii::t('app', 'Error'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMessage()
    {
        return $this->hasOne(\TRS\AsyncNotification\models\MailMessage::className(), ['id' => 'message_id']);
    }




}

<?php

namespace TRS\AsyncNotification\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_message".
 *
 * @property integer $id
 * @property string $status
 * @property string $subject
 * @property string $from
 * @property string $body_text
 * @property string $body_html
 * @property string $created_at
 * @property string $updated_at
 *
 * @property \TRS\AsyncNotification\models\MailAttachment[] $mailAttachments
 * @property \TRS\AsyncNotification\models\MailMessageError[] $mailMessageErrors
 * @property \TRS\AsyncNotification\models\MailRecipient[] $mailRecipients
 */
class MailMessage extends \yii\db\ActiveRecord
{



    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_message';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['subject', 'from', 'body_text', 'body_html'], 'required'],
            [['body_text', 'body_html'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['status', 'subject', 'from'], 'string', 'max' => 255]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'status' => Yii::t('app', 'Status'),
            'subject' => Yii::t('app', 'Subject'),
            'from' => Yii::t('app', 'From'),
            'body_text' => Yii::t('app', 'Body Text'),
            'body_html' => Yii::t('app', 'Body Html'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailAttachments()
    {
        return $this->hasMany(\TRS\AsyncNotification\models\MailAttachment::className(), ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailMessageErrors()
    {
        return $this->hasMany(\TRS\AsyncNotification\models\MailMessageError::className(), ['message_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getMailRecipients()
    {
        return $this->hasMany(\TRS\AsyncNotification\models\MailRecipient::className(), ['message_id' => 'id']);
    }




}

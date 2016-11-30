<?php

namespace TRS\AsyncNotification\models\base;

use Yii;

/**
 * This is the base-model class for table "mail_attachment".
 *
 * @property integer $id
 * @property integer $message_id
 * @property string $name
 * @property string $created_at
 *
 * @property \TRS\AsyncNotification\models\MailMessage $message
 */
class MailAttachment extends \yii\db\ActiveRecord
{


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mail_attachment';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [ [ 'message_id' ], 'integer' ],
            [ [ 'created_at' ], 'safe' ],
            [ [ 'name' ], 'string', 'max' => 2048 ]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id'         => Yii::t('app', 'ID'),
            'message_id' => Yii::t('app', 'Message ID'),
            'name'       => Yii::t('app', 'Name'),
            'created_at' => Yii::t('app', 'Created At'),
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

<?php

namespace TRS\AsyncNotification\models;

use TRS\AsyncNotification\components\enums\PushType;
use Yii;
use TRS\AsyncNotification\models\base\PushDevice as BasePushDevice;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "push_device".
 */
class PushDevice extends BasePushDevice
{
    public function rules()
    {
        return ArrayHelper::merge(parent::rules(), [
            ['type', 'in', 'range' => [PushType::ANDROID, PushType::IOS]]
        ]);
    }

}

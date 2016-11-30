<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/18/15
 * Time: 11:54 AM
 */

namespace TRS\AsyncNotification\components\validators;


use yii\validators\Validator;

class FileExists extends Validator
{
    protected function validateValue($value)
    {
        $realPath = realpath($value);

        if ($realPath)
            return file_exists($realPath);

        return false;
    }
} 
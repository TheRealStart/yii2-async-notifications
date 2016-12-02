<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/2/15
 * Time: 4:45 PM
 */

namespace TRS\AsyncNotification\components\enums;


class MailStatus
{
    const _NEW       = 'new';
    const IN_PROCESS = 'in_process';
    const SENT       = 'sent';
    const ERROR      = 'error';
    const FAIL       = 'fail';
    const CANCELED   = 'canceled';
} 
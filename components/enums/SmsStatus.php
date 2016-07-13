<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/2/15
 * Time: 4:45 PM
 */

namespace TRS\AsyncNotification\components\enums;


class SmsStatus
{
	const _NEW       = 'new';
	const SEND       = 'sent';
	const ERROR      = 'error';
	const CANCELED   = 'canceled';
}
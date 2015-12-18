<?php
/**
 * Created by PhpStorm.
 * User: alegz
 * Date: 12/18/15
 * Time: 2:00 PM
 */

namespace TRS\AsyncNotification\components\helpers;


class Error {
	public static function processToString(array $errors, $glue = PHP_EOL) {
		$returnString = '';

		foreach ($errors as $attribute => $errorsList)
			foreach ($errorsList as $error)
				$returnString .= sprintf($glue . '%s: "%s"', $attribute, $error);

		return $returnString;
	}
} 